//image slider

const slides = document.querySelectorAll(".slide");
let slideindex = 0;
let intervalid = null;

// initializeSlider();
document.addEventListener("DOMContentLoaded",initializeSlider);

function initializeSlider(){
  if(slides.length > 0){
    slides[slideindex].classList.add("displaySlide");
  intervalid = setInterval(nextSlide,2000);
  }
  
}

function showSlide(){
  if(slideindex >= slides.length){
    slideindex = 0;

  }
  else if(slideindex < 0){
    slideindex = slides.length - 1;
   }
slides.forEach(slide =>{
  slide.classList.remove("displaySlide");
});
slides[slideindex].classList.add("displaySlide");
}

function prevSlide(){
  clearInterval(intervalid);
  slideindex--;
  showSlide(slideindex);
}

function nextSlide(){
  setInterval(intervalid);
  slideindex++;
  showSlide(slideindex);
}