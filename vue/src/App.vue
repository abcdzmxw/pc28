<template>
  <div id="appLoading" v-if="loding">
    <div class="item-loader-container">
      <div class="la-line-scale-pulse-out">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>
  <transition :name="transitionName" >
    <router-view class="router-view"></router-view>
  </transition>
</template>

<style lang="less">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  height: 100%;
  // overflow: auto;
}

#nav {
  // padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
#appLoading {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-justify-content: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-align-items: center;
  -ms-flex-align: center;
  align-items: center;
  z-index: 999999;
  background-color: #040925;
}

.la-line-scale-pulse-out,
.la-line-scale-pulse-out > div {
  position: relative;
  box-sizing: border-box;
}

.la-line-scale-pulse-out {
  display: block;
  font-size: 0;
  color: #4bb3e8;
}

.la-line-scale-pulse-out > div {
  display: inline-block;
  float: none;
  background-color: currentColor;
  border: 0 solid;
}

.la-line-scale-pulse-out > div {
  width: 6px;
  height: 60px;
  margin: 0 3px;
  border-radius: 0;
  -webkit-animation: la-scale-pulse-out 0.9s
    cubic-bezier(0.85, 0.25, 0.37, 0.85) infinite;
  animation: la-scale-pulse-out 0.9s cubic-bezier(0.85, 0.25, 0.37, 0.85)
    infinite;
}

.la-line-scale-pulse-out > div:nth-child(3) {
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}

.la-line-scale-pulse-out > div:nth-child(2),
.la-line-scale-pulse-out > div:nth-child(4) {
  -webkit-animation-delay: -0.7s;
  animation-delay: -0.7s;
}

.la-line-scale-pulse-out > div:first-child,
.la-line-scale-pulse-out > div:nth-child(5) {
  -webkit-animation-delay: -0.5s;
  animation-delay: -0.5s;
}

@-webkit-keyframes la-scale-pulse-out {
  0% {
    -webkit-transform: scaley(1);
    transform: scaley(1);
  }

  50% {
    -webkit-transform: scaley(0.3);
    transform: scaley(0.3);
  }

  to {
    -webkit-transform: scaley(1);
    transform: scaley(1);
  }
}

@keyframes la-scale-pulse-out {
  0% {
    -webkit-transform: scaley(1);
    transform: scaley(1);
  }

  50% {
    -webkit-transform: scaley(0.3);
    transform: scaley(0.3);
  }

  to {
    -webkit-transform: scaley(1);
    transform: scaley(1);
  }
}
 












@keyframes slideInLeft {
  from {
    transform: translate3d(100%, 0, 0);
    opacity: 1;
  }
  to {
    transform: translate3d(0, 0, 0);
    opacity: 1;
  }
}
@keyframes slideInRight {
  from {
    transform: translate3d(-100%, 0, 0);
    opacity: 1;
  }
  to {
    transform: translate3d(0, 0, 0);
    opacity: 1;
  }
}
@keyframes slideLeftOut {
  // from {
  //     transform: translate3d(0, 0, 0);
  //     opacity: 0;
  // }
  // to {
  //     transform: translate3d(100%, 0, 0);
  //     opacity: 0;
  // }
}
@keyframes slideRightOut {
  from {
    transform: translate3d(0, 0, 0);
    opacity: 0;
  }
  to {
    transform: translate3d(-100%, 0, 0);
    opacity: 0;
  }
}
.slide-left-enter-active {
  animation: slideInLeft 0.5s forwards;
  z-index: 5;
}

.slide-left-leave-active {
  animation: slideLeftOut 0.5s forwards;
  z-index: 3;
}
.slide-right-enter-active {
  animation: slideInRight 0.5s forwards;
  z-index: 5;
}

.slide-right-leave-active {
  animation: slideRightOut 0.5s forwards;
  z-index: 3;
}

 .router-view{
        width: 100%;
        height: auto;
        position: absolute;
        top: 0;
        bottom: 0;
        margin: 0 auto;
        -webkit-overflow-scrolling: touch;
    }



</style>
<script>
// import animate from "animate.css";
import "animate.css/animate.min.css";

export default {
  data() {
    return { loding: true, time: null ,  transitionName: "slide-left",};
  },
  methods: {},
    watch: {
    //使用watch 监听$router的变化
    //使用watch 监听$router的变化
    $route(to, from) {
      // 有主级到次级
      if (to.meta.index > from.meta.index) {
        this.transitionName = "slide-left"; // 向左滑动
      } else if (to.meta.index < from.meta.index) {
        // 由次级到主级
        this.transitionName = "slide-right";
      } else {
        this.transitionName = ""; //同级无过渡效果
      }
    },
  },
  created() {
    window.addEventListener("beforeunload", () => {
      // localStorage.setItem("store", JSON.stringify(this.$store.state));
      console.log("浏览器刷新了");
    });
    let that = this;
    this.time = setTimeout(() => {
      that.loding = false;
    }, 1000);
  },
  unmounted() {
    clearTimeout(this.time);
    this.timeDataFun = null;
  },
};
</script>