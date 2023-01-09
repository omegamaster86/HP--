const burger = document.querySelector(".burger");
const nav = document.querySelector(".wrapper");


burger.addEventListener("click",() => {
    nav.classList.toggle("nav-active");
    
    burger.classList.toggle("toggle");
});
