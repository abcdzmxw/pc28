<template>
  <div class="trendChart flex-column">
    <header>
      <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left" @click="changego">
          <van-icon name="arrow-left" color="#fff" size="20px" />
        </div>
        <div
          class="middle fs-18"
          :class="{ active: showTitle }"
          @click="showTitle = !showTitle"
        >
          <span>{{ name }}</span>
          <img src="@/assets/image/sanjiao.png" class="icon" alt="" />
        </div>
        <div class="right" @click="showTime = !showTime">
          <span>{{time}}</span>
        </div>
      </div>
      <div class="blockHeight"></div>
    </header>
    <div class="g-content">
      <!-- 表格 -->
      <div class="table-wrap Pc28 h100">
        <div class="tr tb-head">
          <div class="td issue">
            <span>{{$t('lunshu')}}</span>
            <span class="c_a0a0a0">{{ $t("yilou") }}</span>
          </div>
          <div class="td hezhi">
            <span>{{$t('hezhi')}}</span>
            <!-- <span class="c_a0a0a0">1</span> -->
          </div>
          <div class="td">
            <span>{{$t('多单')}}</span>
            <!-- <span class="c_a0a0a0">2</span> -->
          </div>
          <div class="td">
            <span>{{$t('空单')}}</span>
            <!-- <span class="c_a0a0a0">3</span> -->
          </div>
          <div class="td">
            <span>{{$t('多双')}}</span>
            <!-- <span class="c_a0a0a0">4</span> -->
          </div>
          <div class="td">
            <span>{{$t('空双')}}</span>
            <!-- <span class="c_a0a0a0">5</span> -->
          </div>
          <div class="td">
            <span>{{$t('做多')}}</span>
            <!-- <span class="c_a0a0a0">6</span> -->
          </div>
          <div class="td">
            <span>{{$t('做空')}}</span>
            <!-- <span class="c_a0a0a0">9</span> -->
          </div>
          <div class="td">
            <span>{{$t('平单')}}</span>
            <!-- <span class="c_a0a0a0">7</span> -->
          </div>
          <div class="td">
            <span>{{$t('平双')}}</span>
            <!-- <span class="c_a0a0a0">8</span> -->
          </div>
        </div>
        <div class="mescroll tb-body">
          <div>
            <div
              class="tr"
              v-for="(item, index) in issueList"
              :key="index"
              @click="findList"
            >
              <div class="td issue c_fe5050">{{ item.issue }}</div>
              <div class="td hezhi">
                <span class="ball fs-12 ball-green">{{ item.code }}</span>
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '多单' || item.base[1] == '多单'|| item.join == '多单' || item.join == '多單'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "多单" || item.base[1] == "多单"||item.join == "多单" || item.join == "多單" ? $t('多单') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '空单' || item.base[1] == '空单'|| item.join == '空单' || item.join == '空單'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "空单" || item.base[1] == "空单"||   item.join == "空单" || item.join == "空單" ?$t('空单') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '多双' || item.base[1] == '多双'||item.join == '多双' || item.join == '多雙'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "多双" || item.base[1] == "多双"||item.join == '多双' || item.join == '多雙' ? $t('多双') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '空双' || item.base[1] == '空双'||item.join == '空双' || item.join == '空雙'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "空双" || item.base[1] == "空双"||item.join == '空双' || item.join == '空雙' ?$t('空双') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '做多' || item.base[1] == '做多'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "做多" || item.base[1] == "做多" ? $t('做多') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '做空' || item.base[1] == '做空'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "做空" || item.base[1] == "做空" ? $t('做空') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '平单' || item.base[1] == '平单'||item.base[0] == '平單' || item.base[1] == '平單'
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "平单" || item.base[1] == "平单"||item.base[0] == '平單' || item.base[1] == '平單' ?$t('平单') : ""
                }}
              </div>
              <div
                class="td"
                :class="
                  item.base[0] == '平双' || item.base[1] == '平双'||item.base[0] == '平雙' || item.base[1] == '平雙'  
                    ? 'select'
                    : ''
                "
              >
                {{
                  item.base[0] == "平双" || item.base[1] == "平双"||item.base[0] == '平雙' || item.base[1] == '平雙'   ? $t('平双') : ""
                }}
              </div>
              <!-- <div class="td select">
                {{ findList(item.base) ? "多单" : "" }}
              </div>
              <div class="td select">空单</div>
              <div class="td select">多双</div>
              <div class="td select">空双</div>
              <div class="td select">做多</div>
              <div class="td select">做双</div>
              <div class="td select">平单</div>
              <div class="td select">平空</div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 遮罩层 -->
 
    <!-- 时间选择 -->
    <van-popup
      v-model:show="showTime"
      @click="showTime = false"
      :style="{ width: '100%' }"
      position="bottom"
    >
      <van-datetime-picker
        v-model="currentDate"
        type="date"
        :title="showGourp"
        :min-date="minDate"
        :max-date="maxDate"
      />
    </van-popup>
  </div>
</template>
<script>
import homeApi from "@/api/home";
export default {
  name: "TrendChart",
  components: {},
  data() {
    return {
      name: "",
      showTitle: false,
      showGourp: 0,
      showTime: false,
      minDate: new Date(2018, 0, 1),
      maxDate: new Date(),
      currentDate: new Date(),
      game_id: "",
      issueList: "",
      list: ["多单", "空单", "多双", "空双", "做多", "做空", "平单", "平双"],
      indexOne: "",
      time:''
    };
  },
  watch: {
    game_id(newVal) {
      
      this.showIssueChange();
    },
  },
  methods: {
    findList(base) {
      for (let i = 0; i < this.list.length; i++) {
        if (base["0"] == this.list[i]) {
          console.log(i, this.list[i], "listIndex");
          return i;
        } else if (base["1"] == this.list[i]) {
          return i;
        }
      }
    },
    changego() {
      this.$router.go(-1);
    },
    showIssueChange() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi
        .issueList(this.game_id)
        .then((data) => {
          console.log(data.data);
          this.issueList = data.data;
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
            let  myDate = new Date();
       let time=  myDate.toLocaleDateString();
         this.time=time

    this.showIssueChange();
  },
};
</script>
<style lang="less" scoped>
.trendChart {
  height: 100%;
}
.icon {
  width: 0.33rem;
  height: 0.25rem;
  transform: rotate(0deg);
  -webkit-transform-origin: 50% 35%;
  transform-origin: 50% 35%;
  transition: transform 0.3s;
}
.active {
  span {
    color: #ff7d20;
  }
  .icon {
    transform: rotate(180deg);
    transition: transform 0.3s;
  }
}
.content {
  z-index: 12;
  position: absolute;
  width: 100%;
  background: #1a243f;
  height: 1.18rem;
  top: 1.18rem;
}
.lottery-list {
  background-color: #1a243f;
  padding: 0.1rem;
}
.lottery-item {
  width: 25%;
  float: left;
  text-align: center;
  padding: 0.12rem;
  .text {
    padding: 0.1rem 0;
    border: 1px dashed #ccc;
    border-radius: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
.activeli .text {
  color: red;
  border-color: red;
}
.g-content {
  flex: 1;
  overflow: auto;
}
.table-wrap {
  width: 9rem;
  min-width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
}
.table-wrap.Pc28 {
  width: 13.7rem;
}
.tr {
  display: flex;
}
.td {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  height: 1rem;
  word-break: break-word;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -ms-flex-pack: distribute;
  justify-content: space-around;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-size: 0.35rem;
  border-right: 1px solid #ececec;
  border-bottom: 1px solid #ececec;
}

.tb-head .td {
  height: 1.33rem;
}
.issue {
  width: 2.7rem;
  flex: unset;
}
.hezhi {
  width: 1.33rem;
  flex: unset;
}
.mescroll {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
}
.select {
  color: #fff;
}
.Pc28 .tr .select:nth-child(4n + 3) {
  background-color: #ff4349;
}
.Pc28 .tr .select:nth-child(4n + 4) {
  background-color: #24abe2;
}
.Pc28 .tr .select:nth-child(4n + 5) {
  background-color: #8a0a61;
}
.Pc28 .tr .select:nth-child(4n + 6) {
  background-color: #fccf2e;
}
.ball-green {
  background: linear-gradient(to bottom, #41ff9a, #01ac7a);
}
.ball {
  display: inline-block;
  border-radius: 50%;
  margin: 0.04rem;
  width: 0.6rem;
  height: 0.6rem;
  text-align: center;
  line-height: 0.6rem;
  color: #fff;
  background-position: center center;
  background-size: cover;
  font-size: 0.38rem;
  overflow: hidden;
  position: relative;
  z-index: 1;
}
</style>