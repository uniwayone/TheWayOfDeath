<template>
  <v-container class="pa-0 pt-12" v-touch="{
    left: () => swipe('Left'),
    right: () => swipe('Right'),
  }" v-if="isSchoolSpace">
    <v-row class="ma-0 pa-0 position-sticky-top-0" >
      <v-col cols="12" class="d-flex align-center bg-white py-1 position-relative">
          <transition name="page" mode="out-in">
            <v-text-field
              v-if="isSearching" key="1"
              solo
              class="mo-search-input-text-field"
              v-model="searchKeyword"
              label="请输入您的搜索词"
              prepend-inner-icon="mdi-magnify"
              hide-details
              color="#7879ff"
              dense 
            >
              <v-icon
                  v-if="searchKeyword.trim() !== ''"
                  slot="append"
                  @click="searchKeyword = ''"
                  small
                >
                mdi-close-circle
              </v-icon>
            </v-text-field>
            <p v-else key="2" class="mx-auto mb-0 font-color-gray-heavy" style="padding:7px;">圈子</p>
          </transition>
          <transition name="page" mode="out-in">
          <v-btn text small v-if="isSearching" key="3" class="ml-3 px-0 min-width-0" @click="onFalseSearching">
            取消
          </v-btn>
          <v-btn icon plain v-else key="4" class="position-absolute put-align-center " style="right:16px; top:50%;" @click="isSearching = true">
            <v-icon size="30">
              mdi-magnify
            </v-icon>
          </v-btn>
          </transition>
        </v-col>
    </v-row>
    <v-row class="ma-0">
      <v-container v-if="contentList.length" class="pa-0" v-for="content in contentList" :key="content.id" >
        <v-row class="pa-0 ma-0" v-if="content.contentId == 1 && content.questionnaires">
          <QusetionnairePost :content="content"></QusetionnairePost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 2 && content.votings">
          <VotingPost :content='content'></VotingPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 3 && content.sms">
          <SmsPost :content='content'></SmsPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 4 && content.campus">
          <CampusPost :content='content'></CampusPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 5 && content.anouncements">
          <AnouncementPost :content="content"></AnouncementPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 6 && content.bulletin_boards">
          <BulletinBoardPost :content='content'></BulletinBoardPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 7 && content.repairdata">
          <RepairDataPost :content='content'></RepairDataPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 8 && content.safestudy">
          <SafeStudyPost :content='content'></SafeStudyPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 9 && content.shift_mng">
          <ShiftMngPost :content='content'></ShiftMngPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 10 && content.home_visit">
          <HomeVisitPost :content='content'></HomeVisitPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 11 && content.schoolstory">
          <SchoolStoryPost :content='content'></SchoolStoryPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="(content.contentId == 12 || content.contentId == 1)&& content.questionnaires">
          <QusetionnairePost :content="content"></QusetionnairePost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="(content.contentId == 13 || content.contentId == 2)&& content.votings">
          <VotingPost :content='content'></VotingPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 14 && content.homework">
          <!-- <SmsPost :content='content'></SmsPost> -->
          <HomeworkPost :content='content'></HomeworkPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 15 && content.todayduty">
          <TodayDutyPost :content='content'></TodayDutyPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 16 && content.lattendance">
          <LessonAttendancePost :content="content"></LessonAttendancePost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 17 && content.notifications">
          <NotificationPost :content="content"></NotificationPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 18 && content.evaluations">
          <EvaluationPost :content="content"></EvaluationPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 19 && content.recognitions">
          <RecognitionPost :content="content"></RecognitionPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 20 && content.vacations">
          <VacationPost :content="content"></VacationPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 23 && content.shares">
          <SharePost :content='content'></SharePost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 24 && content.regnames">
          <RegnamePost :content='content'></RegnamePost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 25 && content.classstory">
          <ClassStoryPost :content="content"></ClassStoryPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 26 && content.interclassstory">
          <InterClassStoryPost :content="content"></InterClassStoryPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <!-- <v-row class="pa-0 ma-0" v-else-if="content.contentId == 27 && content.returnteam">
          <ReturnTeam :content="content"></ReturnTeam>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row> -->
      </v-container>
      <InfiniteLoading 
          class="pb-3 w-100"
          @infinite="infiniteHandler"
      >   
          <div slot="spinner">
            <v-row class="pa-3 ma-5 d-flex justify-center align-center pb-16">
              <v-progress-circular
                indeterminate
                color="#7879ff"
              ></v-progress-circular>
            </v-row>
          </div>
          <div slot="no-more" class="pa-3 ma-3 text-center pb-16">
            <v-chip
              class="ma-2"
              color="#999999"
              outlined
              pill
            >
              暂无
              <v-icon right>
                mdi-cancel 
              </v-icon>
            </v-chip>
          </div>
          <div slot="no-results" class="position-relative row m-0 p-2 h-50 d-flex justify-content-center align-items-center">
              <div class="w-100 text-center p-5 m-5 mt-10">
                  <v-icon size="30" color="grey darken-1">
                    mdi-magnify
                  </v-icon>
                  <h5>暂无</h5>
              </div>
          </div>
      </InfiniteLoading>
    </v-row>
  </v-container>
  <v-container v-else class="pa-0 pt-12" v-touch="{
    left: () => swipe('Left'),
    right: () => swipe('Right'),
  }">
    <v-row class="ma-0 pa-0 position-sticky-top-0" >
      <v-col cols="12" class="d-flex align-center bg-white py-1 position-relative">
          <transition name="page" mode="out-in">
            <v-text-field
              v-if="isSearching" key="1"
              solo
              class="mo-search-input-text-field"
              v-model="searchKeyword"
              label="请输入您的搜索词"
              prepend-inner-icon="mdi-magnify"
              hide-details
              color="#7879ff"
              dense 
            >
              <v-icon
                  v-if="searchKeyword.trim() !== ''"
                  slot="append"
                  @click="searchKeyword = ''"
                  small
                >
                mdi-close-circle
              </v-icon>
            </v-text-field>
            <p v-else key="2" class="mx-auto mb-0 font-color-gray-heavy" style="padding:7px;">圈子</p>
          </transition>
          <transition name="page" mode="out-in">
          <v-btn text small v-if="isSearching" key="3" class="ml-3 px-0 min-width-0" @click="onFalseSearching">
            取消
          </v-btn>
          <v-btn icon plain v-else key="4" class="position-absolute put-align-center " style="right:16px; top:50%;" @click="isSearching = true">
            <v-icon size="30">
              mdi-magnify
            </v-icon>
          </v-btn>
          </transition>
        </v-col>
    </v-row>
    <v-row class="ma-0">
      <v-container  v-if="contentList.length" class="pa-0" v-for="content in contentList" :key="content.id">
        <v-row class="pa-0 ma-0" v-if="content.contentId == 1 && content.questionnaires">
          <QusetionnairePost :content="content"></QusetionnairePost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 2 && content.votings">
          <VotingPost :content='content'></VotingPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 3 && content.sms">
          <SmsPost :content='content'></SmsPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 4 && content.campus">
          <CampusPost :content='content'></CampusPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 5 && content.anouncements">
          <AnouncementPost :content="content"></AnouncementPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 6 && content.bulletin_boards">
          <BulletinBoardPost :content='content'></BulletinBoardPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 7 && content.repairdata">
          <RepairDataPost :content='content'></RepairDataPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 8 && content.safestudy">
          <SafeStudyPost :content='content'></SafeStudyPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 9 && content.shift_mng">
          <ShiftMngPost :content='content'></ShiftMngPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 10 && content.home_visit">
          <HomeVisitPost :content='content'></HomeVisitPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 11 && content.schoolstory">
          <SchoolStoryPost :content='content'></SchoolStoryPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="(content.contentId == 12 || content.contentId == 1)&& content.questionnaires">
          <QusetionnairePost :content="content"></QusetionnairePost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="(content.contentId == 13 || content.contentId == 2)&& content.votings">
          <VotingPost :content='content'></VotingPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 14 && content.homework">
          <!-- <SmsPost :content='content'></SmsPost> -->
          <HomeworkPost :content='content'></HomeworkPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 15 && content.todayduty">
          <TodayDutyPost :content='content'></TodayDutyPost>
          <FooterPost :footerInfo='content' @updateFooterInfo="updateFooterInfo"></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 16 && content.lattendance">
          <LessonAttendancePost :content="content"></LessonAttendancePost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 17 && content.notifications">
          <NotificationPost :content="content"></NotificationPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 18 && content.evaluations">
          <EvaluationPost :content="content"></EvaluationPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 19 && content.recognitions">
          <RecognitionPost :content="content"></RecognitionPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 20 && content.vacations">
          <VacationPost :content="content"></VacationPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 23 && content.shares">
          <SharePost :content='content'></SharePost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 24 && content.regnames">
          <RegnamePost :content='content'></RegnamePost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 25 && content.classstory">
          <ClassStoryPost :content="content"></ClassStoryPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <v-row class="pa-0 ma-0" v-else-if="content.contentId == 26 && content.interclassstory">
          <InterClassStoryPost :content="content"></InterClassStoryPost>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row>
        <!-- <v-row class="pa-0 ma-0" v-else-if="content.contentId == 27 && content.returnteam">
          <ReturnTeam :content="content"></ReturnTeam>
          <FooterPost :footerInfo='content' @updateFooterInfo='updateFooterInfo'></FooterPost>
        </v-row> -->
      </v-container>
      <InfiniteLoading 
          class="pb-3 w-100"
          @infinite="infiniteHandler"
      >   
          <div slot="spinner">
            <v-row class="pa-3 ma-5 d-flex justify-center align-center pb-16">
              <v-progress-circular
                indeterminate
                color="#7879ff"
              ></v-progress-circular>
            </v-row>
          </div>
          <div slot="no-more" class="pa-3 ma-3 text-center pb-16">
            <v-chip
              class="ma-2"
              color="#999999"
              outlined
              pill
            >
              暂无
              <v-icon right>
                mdi-cancel 
              </v-icon>
            </v-chip>
          </div>
          <div slot="no-results" class="position-relative row m-0 p-2 h-50 d-flex justify-content-center align-items-center">
              <div class="w-100 text-center p-5 m-5 mt-10">
                  <v-icon size="30" color="grey darken-1">
                    mdi-magnify
                  </v-icon>
                  <h5>暂无</h5>
              </div>
          </div>
      </InfiniteLoading>
    </v-row>
    
  </v-container>
</template>

<script>
import {getSchoolPost, getClassPost} from '~/api/post';
import lang from '~/helper/lang.json'
import {mapGetters} from 'vuex';

import InfiniteLoading from 'vue-infinite-loading';
import FooterPost from '~/components/contents/footerPost'
import QusetionnairePost from '~/components/contents/questionnairePost'
import VotingPost from '~/components/contents/votingPost';
import SmsPost from '~/components/contents/smsPost';
import CampusPost from '~/components/contents/campusPost'
import AnouncementPost from '~/components/contents/anouncementPost'
import BulletinBoardPost from '~/components/contents/bulletinBoardPost'
import HomeVisitPost from '~/components/contents/homeVisitPost'
import SharePost from '~/components/contents/sharePost'
import RegnamePost from '~/components/contents/regnamePost'
import ShiftMngPost from '~/components/contents/shiftMngPost'
import SafeStudyPost from '~/components/contents/safeStudyPost'
import RepairDataPost from '~/components/contents/repairDataPost'
import SchoolStoryPost from '~/components/contents/schoolStoryPost'
import NotificationPost from '~/components/contents/notificationPost'
import HomeworkPost from '~/components/contents/homeworkPost'
import EvaluationPost from '~/components/contents/evaluationPost'
import RecognitionPost from '~/components/contents/recognitionPost'
import HomeworkResultPost from '~/components/contents/homeworkResultPost'
import ClassStoryPost from '~/components/contents/classStoryPost'
import InterClassStoryPost from '~/components/contents/interClassStoryPost'
import ReturnTeam from '~/components/contents/returnTeam'
import VacationPost from '~/components/contents/vacationPost'
export default {

  middleware: 'auth',
  components :{
    QusetionnairePost,
    VotingPost,
    SmsPost,
    FooterPost,
    CampusPost,
    AnouncementPost,
    BulletinBoardPost,
    HomeVisitPost,
    SharePost,
    RegnamePost,
    ShiftMngPost,
    SafeStudyPost,
    RepairDataPost,
    SchoolStoryPost,
    InfiniteLoading,
    NotificationPost,
    HomeworkPost,
    EvaluationPost,
    RecognitionPost,
    HomeworkResultPost,
    ClassStoryPost,
    InterClassStoryPost,
    ReturnTeam,
    VacationPost
  },

    data: ()=> ({
      baseUrl: window.Laravel.base_url,
      isLoadingContents:false,
      attrs: {
        class: 'mb-6',
      },
      contentList: [],
      lang,

      isSearching: false,
      searchKeyword: '',

      //infinit loading
      pageOfContent: 1,
      lastPageOfContent: 0,
    }),

    computed:{
      ...mapGetters({
        isSchoolSpace: 'mo/isSchoolSpace',
        selectedSchoolItem: 'mo/selectedSchoolItem',
        user: 'auth/user'
      }),
      currentPath(){
        return this.$route
      }
    },
    watch:{
      currentPath:{
        handler(val){
          if(val.query.fix){
              let index = this.contentList.findIndex(content=>content.id == this.currentPath.query.fix)
              if(index > -1){
                  let fixVal = this.contentList[index]
                  this.contentList.splice(index,1)
                  let currentTime = this.TimeView(Date.now())
                  fixVal.fixTop = currentTime;
                  this.contentList.unshift(fixVal)
              }
          }
          if(val.query.release){
              let index = this.contentList.findIndex(content=>content.id == this.currentPath.query.release)
              if(index > -1){
                  let releaseVal = this.contentList[index]
                  releaseVal.fixTop = null
                  this.contentList.splice(index,1)
                  this.contentList.push(releaseVal)
              }
          }
          if(val.query.remove){
              let index = this.contentList.findIndex(content=>content.id == this.currentPath.query.remove)
              if(index > -1){
                  this.contentList.splice(index,1)
              }
          }
        },
        deeper:true
      }
    },
    created(){
      if(this.selectedSchoolItem == null){
        this.$router.push({name: 'home'});
      }
      ("this.isSchoolSpace", this.isSchoolSpace, this.selectedSchoolItem);
    },

    methods:{
      onSearch(){
      },
      onFalseSearching(){
        this.isSearching = false;
        this.searchKeyword = '';
      },
      swipe (direction) {
        if(direction == "Left"){
          this.$router.push({name: 'profile.list'});
        }
        if(direction == "Right"){
          this.$router.push({name: 'mochat.contact'});
        }
      },

      updateFooterInfo(data){
        let index = this.contentList.findIndex(content=>content.id === data.id)
        if(index > -1){
          
        }
      },

      async infiniteHandler($state){
        if(this.isSchoolSpace == true ){
          let timeOut = 0;
          this.isLoadingContents = true;
          if (this.pageOfContent > 1) {
              timeOut = 1000;
          }
          let vm = this;
          
          await getSchoolPost({page:this.pageOfContent,schoolId:this.selectedSchoolItem.schoolId})
          .then(res=>{
              if(vm.pageOfContent == 1 && res.data.data.length == 0){
                  $state.complete();
                  return;
              }
              vm.lastpageOfContent = res.data.last_page;
              $.each(res.data.data, function(key, value){
                
                  vm.contentList.push(value); 
              });
              if (vm.pageOfContent - 1 === vm.lastpageOfContent) {
                  $state.complete();
              }
              else {
                  $state.loaded();
              }
              vm.pageOfContent = vm.pageOfContent + 1;
          });
          this.isLoadingContents = false;
        }
        else{
          let timeOut = 0;
          this.isLoadingContents = true;
          if (this.pageOfContent > 1) {
              timeOut = 1000;
          }
          let vm = this;

          await getClassPost(this.selectedSchoolItem.lessonId, this.pageOfContent)
          .then(res=>{
              if(vm.pageOfContent == 1 && res.data.data.length == 0){
                  $state.complete();
                  return;
              }
              vm.lastpageOfContent = res.data.last_page;
              
              $.each(res.data.data, function(key, value){
                  vm.contentList.push(value); 
              });
              if (vm.pageOfContent - 1 === vm.lastpageOfContent) {
                  $state.complete();
              }
              else {
                  $state.loaded();
              }
              vm.pageOfContent = vm.pageOfContent + 1;
          });
          this.isLoadingContents = false;
        }
      },
    }
}
</script>

<style>

</style>