<template>
    <v-container class="pa-0">
        <RouterBack title="发布"></RouterBack>
        <v-row v-for="(itemGroup, i) in contentItemList" :key="i" class="ma-0 px-6">
            <v-col cols="12">
                <v-chip
                    class="ma-2 px-5"
                    color="#7879ff"
                    outlined
                >
                    <v-icon left>
                        {{itemGroup.icon}}
                    </v-icon>
                    {{itemGroup.title}}
                </v-chip>
            </v-col>
            <v-col v-for="(item, j) in itemGroup.items" :key="j" cols="12" sm="6" md="4" lg="3">
                <PostItem 
                    :item="item" 
                    @selected="selectedPost"
                    :btnTxt="'发布'"
                />
            </v-col>
        </v-row>
        <transition name="fade" mode="out-in">
            <router-view :key="$router.path"></router-view>
        </transition>
    </v-container>
</template>

<script>
import { mapGetters } from 'vuex'
import PostItem from '~/components/postItem'
import RouterBack from '~/components/routerBack'
import {getDashboardData} from '~/api/tablet'
export default {
    components:{
        PostItem,
        RouterBack
    },

    computed: {
        ...mapGetters({
            user:'auth/user'
        }),
        currentPath(){
            return this.$route
        },
    },
    watch:{
        currentPath:{
            handler(val){
                //console.log('&&&&&&&&&',val)
                if(val.name == 'classSpace.post'){
                    this.isPostItem = true
                }
            },
            deep:true
        }
    },
    created(){
       if(this.currentPath.name == 'classSpace.post'){
            this.isPostItem = true
        }
        // getDashboardData().then(res=>{
        //     console.log(res.data)
        // }).catch(err=>{
        //     console.log(err.response)
        // })
        switch(this.user.roleId){
            case 1:
                this.contentItemList = this.adminItem
                break;
            case 2:
                this.contentItemList = this.managerItem
                break;
            case 3:
                this.contentItemList = this.teacherItem
                break;
            case 4:
                this.contentItemList = this.parentItem
                break;
            case 5:
                this.contentItemList = this.studentItem
                break;
            default:
                this.contentItemList = this.studentItem
                break;
            case 7:
                this.contentItemList = this.teacherItem;
                break
        }
    },
    data: () => ({
        currentSchoolId : -1,
        isPostItem:false,
        contentItemList : [
            
        ],
        adminItem:[
            // {
            //     title:"智能考勤",//지능출근
            //     icon:"mdi-calendar-month-outline",
            //     items:[
                    // {
                    //     color:"#3EBBE8",
                    //     title:"课程维护",//수업유지
                    //     imgUrl:"/asset/img/newIcon/schedule.png",
                    //     path:"admin.schedule"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假单",//휴가신청
                    //     imgUrl:"/asset/img/newIcon/vocationRequest.png",
                    //     path:"posts.vocation"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假审批",//휴가심사
                    //     imgUrl:"/asset/img/newIcon/vocationReply.png",
                    //     path:"posts.vocation"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"考勤",//출근
                    //     imgUrl:"/asset/img/newIcon/attendance.png",
                    //     path:"posts.attendance"
                    // },
            //         {
            //             color:"#3EBBE8",
            //             title:"课程表",//시간표
            //             imgUrl:"/asset/img/newIcon/scheduleClass.png",
            //             path:"admin.scheduleClass"
            //         },
            //     ]
            // },
            {
                title:"基础沟通",//기초의사소통
                icon:"mdi-message-text",
                items:[
                    // {
                    //     color:"#B673E0",
                    //     title:"通讯录",//주소록
                    //     imgUrl:"/asset/img/newIcon/member.png",
                    //     path:"schoolSpace.member"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"文件",//문건
                    //     imgUrl:"/asset/img/newIcon/file.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"话题",//화제
                    //     imgUrl:"/asset/img/newIcon/discussion.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#B673E0",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share1.png",
                        path:"posts.Cshare"
                    },
                    {
                        color:"#B673E0",
                        title:"问卷",//설문
                        imgUrl:"/asset/img/newIcon/questionnaire.png",
                        path:"posts.Cquestionnaire"
                    },
                    {
                        color:"#B673E0",
                        title:"投票",//투표
                        imgUrl:"/asset/img/newIcon/voting.png",
                        path:"posts.Cvoting"
                    },
                    {
                        color:"#B673E0",
                        title:"报名",//이름등록
                        imgUrl:"/asset/img/newIcon/regName.png",
                        path:"posts.CregName"
                    },
                ]
            },
            {
                title:"家校互动",//가교호상활동
                icon:"mdi-nfc-tap",
                items:[
                    
                    {
                        color:"#E4BC16",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share2.png",
                        path:"posts.Cshare"
                    },
                    {
                        color:"#E4BC16",
                        title:"公告",//공시
                        imgUrl:"/asset/img/newIcon/announcement.png",
                        path:"posts.Cannouncement"
                    },
                    // {
                    //     color:"#E4BC16",
                    //     title:"作业",//숙제
                    //     imgUrl:"/asset/img/newIcon/homework.png",
                    //     path:"posts.Chomework"
                    // },
                ]
            },
            {
                title:"校园安全",//교내안전
                icon:"mdi-account-cog-outline",
                items:[
                    // {
                    //     color:"#98BB3A",
                    //     title:"访客管理",//방문자관리
                    //     imgUrl:"/asset/img/newIcon/manageGuests.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"归程队",//귀한팀관리
                    //     imgUrl:"/asset/img/newIcon/returnTeam.png",
                    //     path:"classSpace.applications.returnTeam"
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"打卡管理",//카드긋기
                    //     imgUrl:"/asset/img/newIcon/cardcheck.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#98BB3A",
                        title:"维修工单",//수리공
                        imgUrl:"/asset/img/newIcon/repair.png",
                        path:"posts.Crepair"
                    },
                    {
                        color:"#98BB3A",
                        title:"安全教育",//안전교육
                        imgUrl:"/asset/img/newIcon/safeStudy.png",
                        path:"posts.CsafeStudy"
                    },
                ]
            },
            {
                title:"校园文化",//
                icon:"mdi-lifebuoy",
                items:[
                    {
                        color:"#C95384",
                        title:"班级动态",//학급동태
                        imgUrl:"/asset/img/newIcon/classStory.png",
                        path:"posts.classStory",

                    },
                    {
                        color:"#C95384",
                        title:"班际动态",//학급별동태
                        imgUrl:"/asset/img/newIcon/interClassStory.png",
                        path:"posts.interClassStory",
                    },
                    {
                        color:"#C95384",
                        title:"表彰",//영예표창
                        imgUrl:"/asset/img/newIcon/recognition.png",
                        path:"posts.Crecognition",

                    },
                ]
            },
        ],
        managerItem:[
            // {
            //     title:"智能考勤",//지능출근
            //     icon:"mdi-calendar-month-outline",
                // items:[
                    // {
                    //     color:"#3EBBE8",
                    //     title:"课程维护",//수업유지
                    //     imgUrl:"/asset/img/newIcon/schedule.png",
                    //     path:"admin.schedule"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假单",//휴가신청
                    //     imgUrl:"/asset/img/newIcon/vocationRequest.png",
                    //     path:"posts.vocation"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假审批",//휴가심사
                    //     imgUrl:"/asset/img/newIcon/vocationReply.png",
                    //     path:"posts.vocation"
                    // },
            //         {
            //             color:"#3EBBE8",
            //             title:"考勤",//출근
            //             imgUrl:"/asset/img/newIcon/attendance.png",
            //             path:"posts.attendance"
            //         },
            //         {
            //             color:"#3EBBE8",
            //             title:"课程表",//시간표
            //             imgUrl:"/asset/img/newIcon/scheduleClass.png",
            //             path:"admin.scheduleClass"
            //         },
            //     ]
            // },
            {
                title:"基础沟通",//기초의사소통
                icon:"mdi-message-text",
                items:[
                    // {
                    //     color:"#B673E0",
                    //     title:"通讯录",//주소록
                    //     imgUrl:"/asset/img/newIcon/member.png",
                    //     path:"schoolSpace.member"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"文件",//문건
                    //     imgUrl:"/asset/img/newIcon/file.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"话题",//화제
                    //     imgUrl:"/asset/img/newIcon/discussion.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#B673E0",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share1.png",
                        path:"posts.Cshare"
                    },
                    {
                        color:"#B673E0",
                        title:"问卷",//설문
                        imgUrl:"/asset/img/newIcon/questionnaire.png",
                        path:"posts.Cquestionnaire"
                    },
                    {
                        color:"#B673E0",
                        title:"投票",//투표
                        imgUrl:"/asset/img/newIcon/voting.png",
                        path:"posts.Cvoting"
                    },
                    {
                        color:"#B673E0",
                        title:"报名",//이름등록
                        imgUrl:"/asset/img/newIcon/regName.png",
                        path:"posts.CregName"
                    },
                ]
            },
            {
                title:"家校互动",//가교호상활동
                icon:"mdi-nfc-tap",
                items:[
                    
                    {
                        color:"#E4BC16",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share2.png",
                        path:"posts.Cshare"
                    },
                    {
                        color:"#E4BC16",
                        title:"公告",//공시
                        imgUrl:"/asset/img/newIcon/announcement.png",
                        path:"posts.Cannouncement"
                    },
                    // {
                    //     color:"#E4BC16",
                    //     title:"作业",//숙제
                    //     imgUrl:"/asset/img/newIcon/homework.png",
                    //     path:"posts.Chomework"
                    // },
                ]
            },
            {
                title:"校园安全",//교내안전
                icon:"mdi-account-cog-outline",
                items:[
                    // {
                    //     color:"#98BB3A",
                    //     title:"访客管理",//방문자관리
                    //     imgUrl:"/asset/img/newIcon/manageGuests.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"归程队",//귀한팀관리
                    //     imgUrl:"/asset/img/newIcon/returnTeam.png",
                    //     path:"classSpace.applications.returnTeam"
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"打卡管理",//카드긋기
                    //     imgUrl:"/asset/img/newIcon/cardcheck.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#98BB3A",
                        title:"维修工单",//수리공
                        imgUrl:"/asset/img/newIcon/repair.png",
                        path:"posts.Crepair"
                    },
                    {
                        color:"#98BB3A",
                        title:"安全教育",//안전교육
                        imgUrl:"/asset/img/newIcon/safeStudy.png",
                        path:"posts.CsafeStudy"
                    },
                ]
            },
            {
                title:"校园文化",//
                icon:"mdi-lifebuoy",
                items:[
                    {
                        color:"#C95384",
                        title:"班级动态",//학급동태
                        imgUrl:"/asset/img/newIcon/classStory.png",
                        path:"posts.classStory",

                    },
                    {
                        color:"#C95384",
                        title:"班际动态",//학급별동태
                        imgUrl:"/asset/img/newIcon/interClassStory.png",
                        path:"posts.interClassStory",
                    },
                    {
                        color:"#C95384",
                        title:"表彰",//영예표창
                        imgUrl:"/asset/img/newIcon/recognition.png",
                        path:"posts.Crecognition",

                    },
                ]
            },
        ],
        teacherItem:[
            {
                title:"智能考勤",//지능출근
                icon:"mdi-calendar-month-outline",
                items:[
                    // {
                    //     color:"#3EBBE8",
                    //     title:"课程维护",//수업유지
                    //     imgUrl:"/asset/img/newIcon/schedule.png",
                    //     path:"admin.schedule"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假单",//휴가신청
                    //     imgUrl:"/asset/img/newIcon/vocationRequest.png",
                    //     path:"posts.vocation"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假审批",//휴가심사
                    //     imgUrl:"/asset/img/newIcon/vocationReply.png",
                    //     path:"posts.vocation"
                    // },
            //         {
            //             color:"#3EBBE8",
            //             title:"考勤",//출근
            //             imgUrl:"/asset/img/newIcon/attendance.png",
            //             path:"posts.attendance"
            //         },
                    {
                        color:"#3EBBE8",
                        title:"课程表",//시간표
                        imgUrl:"/asset/img/newIcon/scheduleClass.png",
                        path:"admin.scheduleClass"
                    },
                ]
            },
            {
                title:"基础沟通",//기초의사소통
                icon:"mdi-message-text",
                items:[
                    // {
                    //     color:"#B673E0",
                    //     title:"通讯录",//주소록
                    //     imgUrl:"/asset/img/newIcon/member.png",
                    //     path:"schoolSpace.member"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"文件",//문건
                    //     imgUrl:"/asset/img/newIcon/file.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"话题",//화제
                    //     imgUrl:"/asset/img/newIcon/discussion.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#B673E0",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share1.png",
                        path:"posts.Cshare"
                    },
                    {
                        color:"#B673E0",
                        title:"问卷",//설문
                        imgUrl:"/asset/img/newIcon/questionnaire.png",
                        path:"posts.Cquestionnaire"
                    },
                    {
                        color:"#B673E0",
                        title:"投票",//투표
                        imgUrl:"/asset/img/newIcon/voting.png",
                        path:"posts.Cvoting"
                    },
                    {
                        color:"#B673E0",
                        title:"报名",//이름등록
                        imgUrl:"/asset/img/newIcon/regName.png",
                        path:"posts.CregName"
                    },
                ]
            },
            {
                title:"家校互动",//가교호상활동
                icon:"mdi-nfc-tap",
                items:[
                    
                    {
                        color:"#E4BC16",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share2.png",
                        path:"posts.Cshare"
                    },
                    {
                        color:"#E4BC16",
                        title:"公告",//공시
                        imgUrl:"/asset/img/newIcon/announcement.png",
                        path:"posts.Cannouncement"
                    },
                    {
                        color:"#E4BC16",
                        title:"作业",//숙제
                        imgUrl:"/asset/img/newIcon/homework.png",
                        path:"posts.Chomework"
                    },
                    {
                        color: "#E4BC16",
                        title: "今日值日", //오늘의 의무
                        imgUrl: "/asset/img/newIcon/duty.png",
                        path: "posts.CtodayDuty"
                    }
                ]
            },
            {
                title:"校园安全",//교내안전
                icon:"mdi-account-cog-outline",
                items:[
                    // {
                    //     color:"#98BB3A",
                    //     title:"访客管理",//방문자관리
                    //     imgUrl:"/asset/img/newIcon/manageGuests.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#98BB3A",
                        title:"归程队",//귀한팀관리
                        imgUrl:"/asset/img/newIcon/returnTeam.png",
                        path:"classSpace.applications.returnTeam"
                    },
                    // {
                    //     color:"#98BB3A",
                    //     title:"打卡管理",//카드긋기
                    //     imgUrl:"/asset/img/newIcon/cardcheck.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#98BB3A",
                        title:"维修工单",//수리공
                        imgUrl:"/asset/img/newIcon/repair.png",
                        path:"posts.Crepair"
                    },
                    {
                        color:"#98BB3A",
                        title:"安全教育",//안전교육
                        imgUrl:"/asset/img/newIcon/safeStudy.png",
                        path:"posts.CsafeStudy"
                    },
                ]
            },
            {
                title:"校园文化",//
                icon:"mdi-lifebuoy",
                items:[
                    {
                        color:"#C95384",
                        title:"班级动态",//학급동태
                        imgUrl:"/asset/img/newIcon/classStory.png",
                        path:"posts.classStory",

                    },
                    {
                        color:"#C95384",
                        title:"班际动态",//학급별동태
                        imgUrl:"/asset/img/newIcon/interClassStory.png",
                        path:"posts.interClassStory",
                    },
                    {
                        color:"#C95384",
                        title:"表彰",//영예표창
                        imgUrl:"/asset/img/newIcon/recognition.png",
                        path:"posts.Crecognition",

                    },
                ]
            },
        ],
        parentItem:[
            // {
                // title:"智能考勤",//지능출근
                // icon:"mdi-calendar-month-outline",
                // items:[
                    // {
                    //     color:"#3EBBE8",
                    //     title:"课程维护",//수업유지
                    //     imgUrl:"/asset/img/newIcon/schedule.png",
                    //     path:"admin.schedule"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假单",//휴가신청
                    //     imgUrl:"/asset/img/newIcon/vocationRequest.png",
                    //     path:"posts.vocation"
                    // },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假审批",//휴가심사
                    //     imgUrl:"/asset/img/newIcon/vocationReply.png",
                    //     path:"posts.vocation"
                    // },
            //         {
            //             color:"#3EBBE8",
            //             title:"考勤",//출근
            //             imgUrl:"/asset/img/newIcon/attendance.png",
            //             path:"posts.attendance"
            //         },
            //         {
            //             color:"#3EBBE8",
            //             title:"课程表",//시간표
            //             imgUrl:"/asset/img/newIcon/scheduleClass.png",
            //             path:"admin.scheduleClass"
            //         },
            //     ]
            // },
            {
                title:"基础沟通",//기초의사소통
                icon:"mdi-message-text",
                items:[
                    // {
                    //     color:"#B673E0",
                    //     title:"通讯录",//주소록
                    //     imgUrl:"/asset/img/newIcon/member.png",
                    //     path:"schoolSpace.member"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"文件",//문건
                    //     imgUrl:"/asset/img/newIcon/file.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"话题",//화제
                    //     imgUrl:"/asset/img/newIcon/discussion.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#B673E0",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share1.png",
                        path:"posts.Cshare"
                    },
                    // {
                    //     color:"#B673E0",
                    //     title:"问卷",//설문
                    //     imgUrl:"/asset/img/newIcon/questionnaire.png",
                    //     path:"posts.Cquestionnaire"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"投票",//투표
                    //     imgUrl:"/asset/img/newIcon/voting.png",
                    //     path:"posts.Cvoting"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"报名",//이름등록
                    //     imgUrl:"/asset/img/newIcon/regName.png",
                    //     path:"posts.regname"
                    // },
                ]
            },
            {
                title:"家校互动",//가교호상활동
                icon:"mdi-nfc-tap",
                items:[
                    
                    {
                        color:"#E4BC16",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share2.png",
                        path:"posts.Cshare"
                    },
                    // {
                    //     color:"#E4BC16",
                    //     title:"公告",//공시
                    //     imgUrl:"/asset/img/newIcon/announcement.png",
                    //     path:"posts.announcement"
                    // },
                    // {
                    //     color:"#E4BC16",
                    //     title:"作业",//숙제
                    //     imgUrl:"/asset/img/newIcon/homework.png",
                    //     path:"posts.Chomework"
                    // },
                ]
            },
            // {
            //     title:"校园安全",//교내안전
            //     icon:"mdi-account-cog-outline",
            //     items:[
                    // {
                    //     color:"#98BB3A",
                    //     title:"访客管理",//방문자관리
                    //     imgUrl:"/asset/img/newIcon/manageGuests.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"归程队",//귀한팀관리
                    //     imgUrl:"/asset/img/newIcon/returnTeam.png",
                    //     path:"classSpace.applications.returnTeam"
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"打卡管理",//카드긋기
                    //     imgUrl:"/asset/img/newIcon/cardcheck.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"维修工单",//수리공
                    //     imgUrl:"/asset/img/newIcon/repair.png",
                    //     path:"posts.Crepair"
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"安全教育",//안전교육
                    //     imgUrl:"/asset/img/newIcon/safeStudy.png",
                    //     path:"posts.safeStudy"
                    // },
            //     ]
            // },
            // {
            //     title:"校园文化",//
            //     icon:"mdi-lifebuoy",
            //     items:[
                    // {
                    //     color:"#C95384",
                    //     title:"班级动态",//학급동태
                    //     imgUrl:"/asset/img/newIcon/classStory.png",
                    //     path:"posts.classStory",

                    // },
                    // {
                    //     color:"#C95384",
                    //     title:"班际动态",//학급별동태
                    //     imgUrl:"/asset/img/newIcon/interClassStory.png",
                    //     path:"posts.interClassStory",
                    // },
                    // {
                    //     color:"#C95384",
                    //     title:"表彰",//영예표창
                    //     imgUrl:"/asset/img/newIcon/recognition.png",
                    //     path:"posts.Crecognition",

                    // },
            //     ]
            // },
        ],
        studentItem:[
            {
                title:"智能考勤",//지능출근
                icon:"mdi-calendar-month-outline",
                items:[
                    // {
                    //     color:"#3EBBE8",
                    //     title:"课程维护",//수업유지
                    //     imgUrl:"/asset/img/newIcon/schedule.png",
                    //     path:"admin.schedule"
                    // },
                    {
                        color:"#3EBBE8",
                        title:"请假单",//휴가신청
                        imgUrl:"/asset/img/newIcon/vocationRequest.png",
                        path:"posts.Cvacation"
                    },
                    // {
                    //     color:"#3EBBE8",
                    //     title:"请假审批",//휴가심사
                    //     imgUrl:"/asset/img/newIcon/vocationReply.png",
                    //     path:"posts.vocation"
                    // },
            //         {
            //             color:"#3EBBE8",
            //             title:"考勤",//출근
            //             imgUrl:"/asset/img/newIcon/attendance.png",
            //             path:"posts.attendance"
            //         },
            //         {
            //             color:"#3EBBE8",
            //             title:"课程表",//시간표
            //             imgUrl:"/asset/img/newIcon/scheduleClass.png",
            //             path:"admin.scheduleClass"
            //         },
                ]
            },
            {
                title:"基础沟通",//기초의사소통
                icon:"mdi-message-text",
                items:[
                    // {
                    //     color:"#B673E0",
                    //     title:"通讯录",//주소록
                    //     imgUrl:"/asset/img/newIcon/member.png",
                    //     path:"schoolSpace.member"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"文件",//문건
                    //     imgUrl:"/asset/img/newIcon/file.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"话题",//화제
                    //     imgUrl:"/asset/img/newIcon/discussion.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#B673E0",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share1.png",
                        path:"posts.Cshare"
                    },
                    // {
                    //     color:"#B673E0",
                    //     title:"问卷",//설문
                    //     imgUrl:"/asset/img/newIcon/questionnaire.png",
                    //     path:"posts.Cquestionnaire"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"投票",//투표
                    //     imgUrl:"/asset/img/newIcon/voting.png",
                    //     path:"posts.Cvoting"
                    // },
                    // {
                    //     color:"#B673E0",
                    //     title:"报名",//이름등록
                    //     imgUrl:"/asset/img/newIcon/regName.png",
                    //     path:"posts.regname"
                    // },
                ]
            },
            {
                title:"家校互动",//가교호상활동
                icon:"mdi-nfc-tap",
                items:[
                    
                    {
                        color:"#E4BC16",
                        title:"分享",//공유
                        imgUrl:"/asset/img/newIcon/share2.png",
                        path:"posts.Cshare"
                    },
                    // {
                    //     color:"#E4BC16",
                    //     title:"公告",//공시
                    //     imgUrl:"/asset/img/newIcon/announcement.png",
                    //     path:"posts.announcement"
                    // },
                    // {
                    //     color:"#E4BC16",
                    //     title:"作业",//숙제
                    //     imgUrl:"/asset/img/newIcon/homework.png",
                    //     path:"posts.Chomework"
                    // },
                ]
            },
            {
                title:"校园安全",//교내안전
                icon:"mdi-account-cog-outline",
                items:[
                    // {
                    //     color:"#98BB3A",
                    //     title:"访客管理",//방문자관리
                    //     imgUrl:"/asset/img/newIcon/manageGuests.png",
                    //     path:"posts."
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"归程队",//귀한팀관리
                    //     imgUrl:"/asset/img/newIcon/returnTeam.png",
                    //     path:"classSpace.applications.returnTeam"
                    // },
                    // {
                    //     color:"#98BB3A",
                    //     title:"打卡管理",//카드긋기
                    //     imgUrl:"/asset/img/newIcon/cardcheck.png",
                    //     path:"posts."
                    // },
                    {
                        color:"#98BB3A",
                        title:"维修工单",//수리공
                        imgUrl:"/asset/img/newIcon/repair.png",
                        path:"posts.Crepair"
                    },
                    // {
                    //     color:"#98BB3A",
                    //     title:"安全教育",//안전교육
                    //     imgUrl:"/asset/img/newIcon/safeStudy.png",
                    //     path:"posts.safeStudy"
                    // },
                ]
            },
            // {
            //     title:"校园文化",//
            //     icon:"mdi-lifebuoy",
            //     items:[
                    // {
                    //     color:"#C95384",
                    //     title:"班级动态",//학급동태
                    //     imgUrl:"/asset/img/newIcon/classStory.png",
                    //     path:"posts.classStory",

                    // },
                    // {
                    //     color:"#C95384",
                    //     title:"班际动态",//학급별동태
                    //     imgUrl:"/asset/img/newIcon/interClassStory.png",
                    //     path:"posts.interClassStory",
                    // },
                    // {
                    //     color:"#C95384",
                    //     title:"表彰",//영예표창
                    //     imgUrl:"/asset/img/newIcon/recognition.png",
                    //     path:"posts.Crecognition",

                    // },
            //     ]
            // },
        ]
    }),
    methods:{
        selectedPost(val){
            this.isPostItem = val
        }
    }
}
</script>

<style>

</style>