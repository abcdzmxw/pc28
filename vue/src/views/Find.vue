<template>
  <div class="find">
    <div>
      
      <div class="lengend">
        <div class="m-bar">
          <div class="line">
            <div class="line-big"></div>
          </div>
          <div class="bar-text">{{ $t("做多") }}</div>
        </div>
        <div class="m-bar">
          <div class="line">
            <div class="line-big"></div>
          </div>
          <div class="bar-text">{{ $t("做空") }}</div>
        </div>
        <div class="m-bar">
          <div class="line">
            <div class="line-small"></div>
            <div class="line-big"></div>
          </div>
          <div class="bar-text">{{ $t("多单") }}</div>
        </div>
        <div class="m-bar">
          <div class="line">
            <div class="line-small"></div>
            <div class="line-big"></div>
          </div>
          <div class="bar-text">{{ $t("多双") }}</div>
        </div>
        <div class="m-bar">
          <div class="line">
            <div class="line-small"></div>
            <div class="line-big"></div>
          </div>
          <div class="bar-text">{{ $t("空单") }}</div>
        </div>
        <div class="m-bar">
          <div class="line">
            <div class="line-small"></div>
            <div class="line-big"></div>
          </div>
          <div class="bar-text">{{ $t("空双") }}</div>
        </div>
      </div>
      <div
        id="echartContainer"
        ref="echartContainer"
        style="width: 100%; height: 500px"
      ></div>
    </div>
    <tab-bar></tab-bar>
  </div>
</template>

<script>
// @ is an alias to /src
import homeApi from "@/api/home";
import TabBar from "@/components/tabbar/TabBar.vue";
var echarts = require("echarts");
export default {
  name: "Find",
  components: {
    TabBar,
  },
  data() {
    return {
      data: [],
      gameList: "",
    };
  },
  methods: {
    onClick(id) {
      console.log("触发", id);
      this.KlineBlock(id);
    },
    KlineBlock(id) {
      let taht = this;
      let issue = [],
        candlist = [];
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi.cand(id).then((data) => {
        console.log(data.data);
        for (let i = 0; i < data.data.length; i++) {
          issue.push(data.data[i].issue);
          candlist.push({
            value: data.data[i].cand,
            sum: data.data[i].cand_code,
          });
        }
        let option = {
          tooltip: {
            trigger: "axis",
            axisPointer: {
              type: "cross",
              lineStyle: {
                width: 0.9,
                opacity: 0.5,
              },
            },
            formatter: function (e) {
              console.log(e);
              return (
                "\n" +
                taht.$t("lunshu") +
                ":" +
                e[0].name +
                "</br>\n" +
                taht.$t("hezhi") +
                ":" +
                (e[0].data.sum || 0) +
                "</br>\n                   "
              );
            },
          },

          grid: {
            //直角坐标系
            top: "5%",
            left: "5%", //grid组件离容器左侧的距离
            right: "2%",
            bottom: "5%",
            //backgroundColor:'#ccc'
          },
          xAxis: {
            data: issue,
            splitLine: {
              show: false,
            },
            axisTick: {
              //y轴刻度线
              show: false,
            },
          },
          yAxis: {
            splitLine: { show: false },
          },
          dataZoom: [
            {
              type: "inside",
              xAxisIndex: [0, 1],
              start: 0,
              end: 100,
            },
          ],
          series: [
            //图表类型
            {
              name: "多单",
              type: "candlestick", //K线图
              data: candlist,
              scale: false,
              axisLine: { onZero: false },
              splitLine: { show: false },
              min: "dataMin",
              max: "dataMax",
              itemStyle: {
                color: "#df3536",
                color0: "#35df7e",
                borderColor: null,
                borderColor0: null,
              },
            },
          ],
        };
        var charts = echarts.init(this.$refs.echartContainer);
        charts.setOption(option);
        this.$toast.clear();
      });
    },
  },
  mounted() {
    // console.log(issue, JSON.stringify(candlist));
    homeApi.Lotteryinfo("", "").then((data) => {
      console.log(data.data);
      this.gameList = data.data.gameList;
      this.KlineBlock(data.data.gameList[0].id);
    });
  },
};
</script>
<style lang="less" scoped>
.g-header {
  position: absolute;
  width: 100%;
  height: 1.18rem;
  text-align: center;
  // background: red;
  background: rgb(14, 21, 48);
  z-index: 1;
  font-size: 0.35rem;
  color: #fff;
}
.lengend {
  margin-top: 0.6rem;
  display: flex;
  padding: 0 20px;
}
.m-bar {
  display: flex;
  align-items: center;
  margin-right: 10px;
}
.bar-text {
  margin-left: 6px;
}
.line {
  display: flex;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  height: 20px;
}
.lengend .m-bar:nth-child(1) .line > div,
.lengend .m-bar:nth-child(3) .line > div,
.lengend .m-bar:nth-child(4) .line > div {
  background: #df3536;
}
.lengend .m-bar:nth-child(1) .line > div,
.lengend .m-bar:nth-child(3) .line > div,
.lengend .m-bar:nth-child(4) .line > div {
  background: #df3536;
}
.lengend .m-bar:nth-child(1) .line > div,
.lengend .m-bar:nth-child(3) .line > div,
.lengend .m-bar:nth-child(4) .line > div {
  background: #df3536;
}
.line .line-big {
  -webkit-box-flex: 2;
  -ms-flex: 2;
  flex: 2;
  width: 5px;
}
.line-small {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  width: 1px;
}
.lengend .m-bar:nth-child(3) .line,
.lengend .m-bar:nth-child(5) .line {
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
}
.lengend .m-bar:nth-child(2) .line > div,
.lengend .m-bar:nth-child(5) .line > div,
.lengend .m-bar:nth-child(6) .line > div {
  background: #35df7e;
}
.lengend .m-bar:nth-child(1) .line > div,
.lengend .m-bar:nth-child(3) .line > div,
.lengend .m-bar:nth-child(4) .line > div {
  background: #df3536;
}
// .lengend .m-bar:nth-child(1) .line > div,
// .lengend .m-bar:nth-child(3) .line > div,
// .lengend .m-bar:nth-child(4) .line > div {
//   background: #df3536;
// }
// .lengend .m-bar:nth-child(1) .line > div,
// .lengend .m-bar:nth-child(3) .line > div,
// .lengend .m-bar:nth-child(4) .line > div {
//   background: #df3536;
// }
// .lengend .m-bar:nth-child(2) .line > div,
// .lengend .m-bar:nth-child(5) .line > div,
// .lengend .m-bar:nth-child(6) .line > div {
//   background: #35df7e;
// }
// .lengend .m-bar:nth-child(2) .line > div,
// .lengend .m-bar:nth-child(5) .line > div,
// .lengend .m-bar:nth-child(6) .line > div {
//   background: #35df7e;
// }
// .lengend .m-bar:nth-child(2) .line > div,
// .lengend .m-bar:nth-child(5) .line > div,
// .lengend .m-bar:nth-child(6) .line > div {
//   background: #35df7e;
// }
// .lengend .m-bar:nth-child(2) .line > div,
// .lengend .m-bar:nth-child(5) .line > div,
// .lengend .m-bar:nth-child(6) .line > div {
//   background: #35df7e;
// }
</style>