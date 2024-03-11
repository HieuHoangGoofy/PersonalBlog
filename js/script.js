let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   searchForm.classList.remove('active');
   profile.classList.remove('active');
}

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   searchForm.classList.remove('active');
   navbar.classList.remove('active');
}

let searchForm = document.querySelector('.header .flex .search-form');

document.querySelector('#search-btn').onclick = () =>{
   searchForm.classList.toggle('active');
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   navbar.classList.remove('active');
   searchForm.classList.remove('active');
}

document.querySelectorAll('.content-150').forEach(content => {
   if(content.innerHTML.length > 150) content.innerHTML = content.innerHTML.slice(0, 150);
});


const themeButton = document.getElementById('theme-button')
const darkTheme = 'dark-theme'


const selectedTheme = localStorage.getItem('selected-theme')


const getCurrentTheme = () => document.body.classList.contains(darkTheme) ? 'dark' : 'light'



if (selectedTheme){
   document.body.classList[selectedTheme === 'dark' ? 'add' : 'remove'](darkTheme)
}

themeButton.addEventListener('click', () =>{
    document.body.classList.toggle(darkTheme)
    

    localStorage.setItem('selected-theme', getCurrentTheme())
})


// Save the current scroll position
function saveScrollPosition() {
   sessionStorage.setItem('scrollPos', window.scrollY);
}

/*/ Restore the scroll position
function restoreScrollPosition() {
   const scrollPos = sessionStorage.getItem('scrollPos');
   if (scrollPos) {
       window.scrollTo({
           top: parseInt(scrollPos),
           behavior: 'instant'  // 'instant' will scroll instantly without animation
       });
       sessionStorage.removeItem('scrollPos');
   }
}


// Save scroll position before refreshing the page
window.addEventListener('beforeunload', saveScrollPosition);

// Restore scroll position after the page is loaded
window.addEventListener('load', restoreScrollPosition);*/



    





 



