function getJSON() {

    // init xhr and specify I'm getting json back
    const xhr = new XMLHttpRequest();
    xhr.responseType = 'json';

    xhr.onload = function() {
        
        // get and error check data
        const data = xhr.response;
        if (!data) {
            console.error("No data loaded.");
            return;
        }
        
        
        // iterate over array of jsons
        for (const item of data) {

            // get the tag to insert into
            const type = item['course'];
            const root = document.getElementById(type);

            if (!root) {
                console.log(type);
                console.warn(`No section found for course: ${type}`);
                continue;
            }

            // add the article
            root.innerHTML += `<article>
                                    <div class="item-row">
                                        <h4 class="name">${item['name']}</h4>
                                        <img class="pic" src=${item['img']}>
                                        <p class="price">${item['price']}</p>
                                    </div>
                                    <div class="item-row">
                                        <p class="desc">${item['desc']}</p>
                                        <img class="vegan" src=resources/img/${item['category']}>
                                    </div>
                                </article>`;

        }
        
    }

    // callback to lmk if I fail
    xhr.onerror = function() {
        console.error("Failed to load JSON file.");
    };

    // start and send request, executing the prior functions on response
    xhr.open("GET", "./data/menu.json");
    xhr.send();
}
getJSON();