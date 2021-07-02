// init controller
var controllerParallax = new ScrollMagic.Controller({
    globalSceneOptions: {
      triggerHook: "onEnter",
      duration: "300%",
      ease: Linear.easeNone
    }
  });
  
  // build scenes
  new ScrollMagic.Scene({
      triggerElement: "#device-stack"
    })
    .setTween(".parallax-bg", {
      y: "100%"
    })
    //.addIndicators()
    .addTo(controllerParallax);
  
  new ScrollMagic.Scene({
      triggerElement: "#device-stack"
    })
    .setTween("#main-mbp", {
      y: "-30%"
    })
    .addTo(controllerParallax);
  
  new ScrollMagic.Scene({
      triggerElement: "#device-stack"
    })
    .setTween("#main-ios", {
      y: "-40%"
    })
    .addTo(controllerParallax);
  
  var controller = new ScrollMagic.Controller();
  
  $("#device-stack .column.small").css("transform", "translateX(-100%)").css("opacity", 0);
  new ScrollMagic.Scene({
      triggerElement: "#device-stack",
      reverse: false
    })
    .setTween("#device-stack .column.small", {
      x: "0%",
      opacity: 1,
      ease: Bounce.easeOut
    })
    //.addIndicators()
    .addTo(controller);
  
  $("#device-stack .column.large").css("transform", "translateX(100%)").css("opacity", 0);
  new ScrollMagic.Scene({
      triggerElement: "#device-stack",
      reverse: false
    })
    .setTween("#device-stack .column.large", {
      x: "2%",
      opacity: 1,
      ease: Bounce.easeOut
    })
    .addTo(controller);