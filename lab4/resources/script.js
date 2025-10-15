document.addEventListener("DOMContentLoaded", () => {
  getWeather();
  getJoke();
  document.getElementById("new-joke").addEventListener("click", getJoke);
});

// ---- WEATHER ----
function getWeather() {
  const weatherEl = document.getElementById("weather-status");
  const weatherIcon = document.getElementById("weather-icon");
  const weatherSection = document.getElementById("weather-section");

  weatherEl.textContent = "Fetching your weather...";

  const fetchWeather = (lat, lon) => {
    const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,weathercode`;

    fetch(url)
      .then(response => response.json())
      .then(data => {
        const temp = data.current.temperature_2m;
        const code = data.current.weathercode;
        const condition = interpretWeather(code);

        weatherEl.textContent = `It’s ${temp}°C and ${condition}.`;
        weatherSection.className = `card ${getWeatherClass(code)}`;

        // Add icon
        const { src, alt } = getWeatherIcon(code);
        weatherIcon.src = src;
        weatherIcon.alt = alt;
      })
      .catch(() => {
        weatherEl.textContent = "Failed to fetch weather data.";
        weatherIcon.src = "";
      });
  };

  const interpretWeather = code => {
    const map = {
      0: "clear and sunny",
      1: "mostly sunny",
      2: "partly cloudy",
      3: "overcast",
      45: "foggy",
      48: "foggy",
      51: "light drizzle",
      61: "raining",
      71: "snowing",
      80: "rain showers",
      95: "thunderstorms"
    };
    return map[code] || "unpredictable";
  };

  const getWeatherClass = code => {
    if ([0, 1].includes(code)) return "weather-sunny";
    if ([2, 3].includes(code)) return "weather-cloudy";
    if ([61, 80].includes(code)) return "weather-rainy";
    if ([71].includes(code)) return "weather-snowy";
    if ([95].includes(code)) return "weather-thunder";
    if ([45, 48].includes(code)) return "weather-foggy";
    return "";
  };

  // 🖼️ Map weather codes to icon URLs
  const getWeatherIcon = code => {
    const base = "https://cdn-icons-png.flaticon.com/512";
    if ([0, 1].includes(code))
      return { src: `${base}/3222/3222801.png`, alt: "Sunny" };
    if ([2, 3].includes(code))
      return { src: `${base}/1163/1163624.png`, alt: "Cloudy" };
    if ([61, 80].includes(code))
      return { src: `${base}/4150/4150902.png`, alt: "Rainy" };
    if ([71].includes(code))
      return { src: `${base}/5903/5903668.png`, alt: "Snowy" };
    if ([95].includes(code))
      return { src: `${base}/1146/1146869.png`, alt: "Thunderstorm" };
    if ([45, 48].includes(code))
      return { src: `${base}/4150/4150895.png`, alt: "Foggy" };
    return { src: `${base}/3222/3222691.png`, alt: "Unknown weather" };
  };

  if (!navigator.geolocation) {
    weatherEl.textContent = "Geolocation not supported. Showing New York’s weather.";
    fetchWeather(40.7128, -74.0060);
    return;
  }

  navigator.geolocation.getCurrentPosition(
    pos => fetchWeather(pos.coords.latitude, pos.coords.longitude),
    () => {
      weatherEl.textContent = "Couldn’t access your location. Showing New York’s weather.";
      fetchWeather(40.7128, -74.0060);
    },
    { timeout: 5000 }
  );
}

// ---- JOKE ----
function getJoke() {
  const setupEl = document.getElementById("joke-setup");
  const punchlineEl = document.getElementById("joke-punchline");
  const jokeSection = document.getElementById("joke-section");

  fetch("https://official-joke-api.appspot.com/random_joke")
    .then(res => res.json())
    .then(joke => {
      setupEl.textContent = joke.setup;
      punchlineEl.textContent = joke.punchline;

      const hue = Math.floor(Math.random() * 360);
      jokeSection.style.backgroundColor = `hsl(${hue}, 70%, 85%)`;
    })
    .catch(() => {
      setupEl.textContent = "Couldn't fetch a joke right now.";
      punchlineEl.textContent = "";
    });
}
