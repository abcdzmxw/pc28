<template>
  <div class="moreNews">
    <header>
      <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left" @click="changego">
          <van-icon name="arrow-left" color="#fff" size="20px" />
        </div>
        <div class="middle fs-18">{{$t('touzikuaixun')}}</div>
        <div class="right">
          <div class="btn-save fs-16"></div>
        </div>
      </div>
      <div class="blockHeight"></div>
    </header>
    <div class="mescroll">
      <div>
        <div class="component-news">
          <div class="hot-topic">
            <h2 class="hot-title fw fs-20">{{$t('remenhuati')}}</h2>
            <div class="hot-content">
              <div
                class="hot-item"
                v-for="(item, index) in tops"
                :key="index"
                @click="changedetail(item)"
              >
                <p class="title">{{ item.title }}</p>
                <p class="join-count fs-10 mr-t5">
                  <span>7706{{$t('renliulan')}}</span>
                </p>
              </div>
            </div>
          </div>
          <div class="media">
            <van-tabs v-model="active" swipeable sticky>
              <van-tab v-for="(item, index) in articleTabs" :key="index">
                <template #title> {{ $t('news'+item.id) }} </template>
                <template #default>
                  <div
                    class="news boderBottom"
                    v-if="item.articles.length != 0"
                  >
                    <div class="news-info">
                      <h3>{{ item.articles }}</h3>
                      <div class="time fs-10 c_a0a0a0">
                        <span class="mr-l20">{{ item.articles }}</span>
                      </div>
                    </div>
                    <img
                      src="@/assets/image/new1.png"
                      class="news-img"
                      alt=""
                    />
                  </div>
                  <div v-else>{{$t('wuTps')}}</div>
                </template>
              </van-tab>
            </van-tabs>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import homeApi from "@/api/home";
export default {
  name: "MoreNews",
  components: {},
  data() {
    return {
      active: 0,
      tops: null,
      articleTabs: null,
    };
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
    info() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi
        .articleList()
        .then((data) => {
          console.log(data.data);
          this.tops = data.data.tops;
          this.articleTabs = data.data.articleTabs;
          this.$toast.clear();
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
    changedetail(item) {
      console.log(item);
      let news = JSON.stringify(item);
      this.$router.push({
        path: "/AticleDetail",
        query: {
          news: news,
        },
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
.moreNews {
  height: 100%;
}
.mescroll {
  flex: 1;
}
.component-news {
  background-color: #1a243f;
  height: 100%;
}
.hot-topic {
  padding: 0.6rem;
}
.hot-content {
  margin-top: 0.2rem;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  flex-wrap: wrap;
}
.hot-item {
  width: 30%;
  background-color: #30385d;
  border-radius: 5px;
  -webkit-box-shadow: 0 1px 3px rgb(0 0 0 / 30%);
  box-shadow: 0 1px 3px rgb(0 0 0 / 30%);
  padding: 0.2rem;
  padding-top: 0.5rem;
  display: flex;
  margin: 0.1rem;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  text-align: left;
  .title {
    min-height: 1.4rem;
    color: #208dff;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    overflow: hidden;
    font-size: 0.4rem;
  }
}
.join-count {
  color: #1b8afa;
  opacity: 0.8;
}
.mr-t5 {
  margin-top: 0.1rem;
}
.hot-title {
  text-align: left;
}
.news {
  padding: 0.3rem;
  display: flex;
}
.news-info {
  margin-right: 0.4rem;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  text-align: left;
}
h3 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
  margin-bottom: 0.3rem;
  flex: 1;
}
.mr-l20 {
  margin-right: 0.4rem;
}
.news-img {
  width: 2.4rem;
  height: 2.2rem;
}
</style>