import store from "@/utils/store";
export default {
    token:store.get('token')?store.get('token'):null,//用户TOken
    currentUserId:store.get('userId')?store.get('userId'):null,
    currentUser:null,
    money:store.get('money')?store.get('money'):null,
    activeList:store.get('activeList')?store.get('activeList'):0,
    group_id:store.get('group_id')?store.get('group_id'):null,//是否游客
    usercrud:store.get('idcard')?store.get('idcard'):null,
}