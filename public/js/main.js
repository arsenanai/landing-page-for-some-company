document.addEventListener('DOMContentLoaded', function () {

  function $(x)   { return document.getElementById(x) }
  function $$(x)  { return Array.prototype.slice.call(document.querySelectorAll(x)) }
  function on(x)  { return document.body.classList.add(x) }
  function off(x) { return document.body.classList.remove(x) }

  $('open_mobile_menu').addEventListener('click', function () {
    on('mobile-menu-active')
  })
  $('close_mobile_menu').addEventListener('click', function () {
    off('mobile-menu-active')
  })

  var prevOffset = 0
  var HEADER_STICKY_POINT = 95
  var offset = window.pageYOffset || document.documentElement.scrollTop
  if (offset > 95) {
    on('site-header-invisible')
  }
  document.addEventListener('scroll', function () {
    var offset = window.pageYOffset || document.documentElement.scrollTop
    if (offset < prevOffset || offset < HEADER_STICKY_POINT) {
      off('site-header-invisible')
    } else {
      on('site-header-invisible')
    }
    prevOffset = offset
  })

  $$('.slider').forEach(function($slider) {
    var imagesList = $slider.querySelector('.images-list').innerText.trim().split(/ +|\n/)
    switch (imagesList.length) {
      case 0: return
      case 1:
        imagesList = [imagesList[0], imagesList[0], imagesList[0], imagesList[0]]
        break
      case 2:
      case 3:
        imagesList = imagesList.concat(imagesList)
        break
    }
    imagesList.forEach(function (url, imgId) {
      var img = document.createElement("img")
      img.setAttribute('src', url)
      img.setAttribute('alt', 'Slide '+(imgId+1))
      $slider.querySelector('.slide' + (imgId+1)).appendChild(img)
    })
    var currentImgId = 0
    var SLIDER_LOCKED = false;
    ['next-slide', 'prev-slide'].forEach(function (className, actionId) {
      $slider.querySelector('.'+className).addEventListener('click', function () {
        if (SLIDER_LOCKED) return
        SLIDER_LOCKED = true
        $slider.classList.add(className)
        var TRANSITIONS_COUNT = 3
        $slider.querySelector('.slide4').addEventListener('transitionend', function () {
          if (--TRANSITIONS_COUNT == 0) {
            switch (actionId) {
              case 0:
                var firstSlide = $slider.querySelector('.slide1')
                var lastSlide = firstSlide.cloneNode(true)
                firstSlide.remove()
                for (var i = 2; i<=4; i++) {
                  var classes = $slider.querySelector('.slide' + i).classList
                  classes.remove('slide' + i)
                  classes.add('slide' + (i-1))
                }
                lastSlide.classList.remove('slide1')
                lastSlide.classList.add('slide4')
                $slider.appendChild(lastSlide)
                break

              case 1:
                var lastSlide = $slider.querySelector('.slide4')
                var firstSlide = lastSlide.cloneNode(true)
                lastSlide.remove()
                for (var i = 1; i<=3; i++) {
                  var classes = $slider.querySelector('.slide' + i).classList
                  classes.remove('slide' + i)
                  classes.add('slide' + (i+1))
                }
                firstSlide.classList.remove('slide4')
                firstSlide.classList.add('slide1')
                $slider.appendChild(firstSlide)
                break
            }
            $slider.classList.remove(className)
            SLIDER_LOCKED = false
          }
        })
      })
    })
  })

})
