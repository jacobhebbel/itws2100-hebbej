
function scrollBehavior(element, offset) {
    element.scrollIntoView({
        behavior: 'smooth'
    });

    setTimeout(() => {
        window.scrollBy({
            top: offset, 
            behavior: 'smooth'
        });
    }, 50);
}

const buttons = document.querySelectorAll('article-button button');

buttons.forEach((buttons, idx) => {

    
});

