<template>
  <div class="lt-list" >
    <div class="lt-item" v-for="(item, index) in list" :key="index">
    
      <div class="t2">{{item.issue.last_issue}}{{$t('lun')}}</div>
      <div class="t3">{{$t('dianwei')}}：{{item.issue.last_code||0}}</div>
    </div>
  </div>
</template>
<script>
import homeApi from "@/api/home";
export default {
  data() {
    return {
      list: [],
    };
  },
  computed: {},
  methods: {
    info() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi
        .Lotteryinfo(1)
        .then((data) => {
          console.log(data.data);
          this.list=data.data.gameList
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
.lt-lis ::-webkit-scrollbar {
  display: none;
}
.lt-list {
  display: flex;
  justify-content: start;
  align-items: stretch;
  padding: 0 0.2rem;
  box-sizing: border-box;
  white-space: nowrap;
  overflow-x: scroll;
  -webkit-overflow-scrolling: touch;
  overflow-y: hidden;
  //    padding-bottom: 20px;
}
::-webkit-scrollbar {
  display: none;
}
.lt-item {
  display: inline-block;
  flex: 1;
  min-width: 3rem;
  margin-right: 0.3rem;
  padding: 0.4rem 0;
  border-radius: 4px;
  background: #1a243f;
  text-align: center;
}
.t1 {
  color: #fff;
  font-size: 0.35rem;
}
.t2 {
  padding: 0.2rem 0.1rem;
  color: #b0b0b0;
  font-size: 0.3rem;
  word-break: break-all;
  white-space: normal;
}
.t3 {
  color: #4bb3e8;
  font-size: 0.34rem;
}
</style>