const WEATHER_API_KEY = 'f677f934185544ab91700258251510';

function getCoordinates() {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      resolve([42.728104, -73.687576]);
      return;
    }
    
    navigator.geolocation.getCurrentPosition(
      res => resolve([res.coords.latitude, res.coords.longitude]),
      err => {
        console.warn(err.message);
        resolve([42.728104, -73.687576]);
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  });
}

function getWeatherData(coords) {
    return new Promise((resolve, reject) => {
        var weatherField = document.getElementById('weatherStatus');
        var weatherImg = document.getElementById('weatherImg');
        var timeField = document.getElementById('timeStatus');

        query = coords.join(',');
        url = `http://api.weatherapi.com/v1/current.json?key=${WEATHER_API_KEY}&q=${query}&aqi=no`;

        fetch(url).then(res => {return res.json()}).then(res => {

            weather = res.current.condition.text;
            imgUrl = res.current.condition.icon;
            time = res.location.localtime.split(' ')[1];

            weatherField.textContent = `The weather is currently ${weather.toLowerCase()}.`;
            weatherImg.src = imgUrl;
            timeField.textContent = `The time is currently ${time}`;
            
            resolve([weather, time]);
            
        }).catch(error => {
            console.log(error);
            weatherField.textContent = 'could not get';
            weatherImg.src = '#';
        });

    });
}

function getCuteFox() {

    var foxImg = document.getElementById('foxImg');
    url = 'https://randomfox.ca/floof';
    fetch(url).then(res => {return res.json()}).then(res => {
        foxImg.src = res.image;
    });

}getCuteFox();

function getOptimalActivity(data) {

    console.log(data);
    weather = data[0].toLowerCase();
    time = data[1];

    if (weather.includes('rain') || weather.includes('overcast')) {

        if (time.split(':')[0] < '12') {
            return 'clean up around the house';
        } else {
            return 'study for a class or read a book';
        }
    } else {
        if (time.split(':')[0] < '12') {
            return 'get some exercise';
        } else {
            return 'go for a walk outside, maybe with some friends';
        }
    }

}

getCoordinates().then(coords => {
    getWeatherData(coords).then(data => {
        const activity = getOptimalActivity(data);
        var tag = document.getElementById('activity');
        tag.textContent = `Because of the weather and time, you should try: ${activity}!`;
    })
});


