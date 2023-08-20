import axios from "axios";
import state from "@/store/state";
// import router from '../router'
// const https = require('https');
require("events").EventEmitter.defaultMaxListeners = 0;
// create an axios instance
const service = axios.create({
  //baseURL: "http://www.mitaobo.com/", //正式 - 服务器
  baseURL: "https://test888.net/", //正式 - 服务器
  timeout: 7000, // request timeout
});
// const ignoreSSL = axios.create({
//   httpsAgent: new https.Agent({
//     rejectUnauthorized: false
//   })
// });
// ignoreSSL.get('https://api.mrjhl.com/');
// // 请求拦截
// const agent = new https.Agent({
//   rejectUnauthorized: false
// });
// axios.get('https://api.mrjhl.com/', { httpsAgent: agent });
service.interceptors.request.use(
  (config) => {
    // do something before request is sent
    const token = state.token;
    if (token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
    }
    if (config.url != "/api/user/login" && config.url != "/api/user/register") {
       config.headers["token"] = token;
    //  config.headers["token"] ='60f13f28-699a-4664-b9fe-88b68c526cf7'
    }
    if (config.method == "post") {
    // console.log(,' window.location.host')

      config.data = {
        ...config.data,
        host:window.location.host,
		lang:localStorage.getItem('locale')||'tw',
      };
    } else if (config.method == "get") {
      config.params = {
        host:window.location.host,
		lang:localStorage.getItem('locale')||'tw',
        ...config.params,
      };
    }
    return config;
  },
  (error) => {
    // do something with request error
    console.log(error); // for debug
    return Promise.reject(error);
  }
);

// 响应拦截
service.interceptors.response.use(
  (response) => {
    const res = response.data;
    // if the custom code is not 20000, it is judged as an error.
    if (res.code !== 1) {
      // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
      if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
        // to re-login
        location.reload();
      }
      if (res.code === 200) {
        return res;
      }

      // const error = new Error(res.msg || "Error");
      const error = res;
      return Promise.reject(error);
    } else {
      return res;
    }
  },
  (err) => {
    // for debug
    // if (err.response.status=== 401) {
    //  return router.push({ path: '/login'});
    // }
    let config = err.config;
    // 如果不是超时直接返回错误
    console.log(config, "configconfig");
    console.log(err.code, "网络波动", err.message.indexOf("timeout"));
    if (
      err.code != "ECONNABORTED" ||
      !config ||
      err.message.indexOf("timeout") === -1
    )
      return Promise.reject(err);
    // 设置本次请求是第几此
    config.__retryCount = config.__retryCount || 1;
    // 当请求超过 7 此后将终止请求，返回错误信息
    if (config.__retryCount >= 7) {
      return Promise.reject(err);
    }
    // 请求次数加 1
    config.__retryCount += 1;
    // 创建一个新 promise 返回给调用接口的函数
    let backoff = new Promise(function (resolve) {
      setTimeout(function () {
        resolve();
      }, 2000);
    });
    // 返回 promise 返回给调用接口的函数，当到达一定时间(1000)后重新请求数据。
    return backoff.then(function () {
      console.log(config, "config返回");
      return service(config);
    });

    // return Promise.reject(err);
  }
);

export default service;
