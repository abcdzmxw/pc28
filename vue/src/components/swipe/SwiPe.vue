<template>
  <van-swipe :autoplay="3000" class="swipe">
    <van-swipe-item v-for="(image, index) in images" :key="index">
      <img :src="image.image"   />
    </van-swipe-item>
  </van-swipe>
</template>

<script>
import homeApi from "@/api/home";
export default {
  data() {
    return {
      images: [],
    };
  },
  computed: {},
  methods: {
    info() {
      homeApi
        .homeindex()
        .then((data) => {
          console.log(data.data);
          this.images = data.data.banner;
          this.$toast.clear();
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
  },
  mounted() {},
  created() {
    this.info();
  },
};
</script>
<style lang="less" scoped>
.swipe {
  height: 4rem;
  img {
    height: 100%;
    width: 100%;
  }
}
</style>