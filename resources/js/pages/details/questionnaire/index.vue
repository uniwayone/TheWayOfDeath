<template>
    <v-container class="pa-0" v-if="$isMobile()">
      <v-container class="pt-0 px-0 h-100 bg-white mb-16 pb-10-px pt-12">
        <v-row class="ma-0 bg-white justify-center position-sticky-top-0" >
          <v-icon @click="$router.go(-1)" size="35" class="position-absolute put-align-center" style="left: 0px; top:50%" >
            mdi-chevron-left
          </v-icon>
          <p class="mb-0 font-size-0-95 font-weight-bold pa-3" >{{lang.questionnaire}}</p>
          <div>
            <v-btn color="#7879ff" :dark="!alreadyAnswer" :disabled="alreadyAnswer" :loading="isSubmit" @click="submit" class="position-absolute put-align-center" style="right:20%;top:50%"> {{lang.submit}} </v-btn>
            <v-btn  v-if="answerUserShow == false" @click="answerUsers"  class="position-absolute put-align-center" dark color="#F19861" style="right: 0px; top:50%">
              已答{{answerDataList.length > 0 ? answerDataList.length : ''}}
            </v-btn>
          </div>
        </v-row>
        <div class="cus-divider-light-gray-height"></div>
        <v-row class="ma-0">
          <v-col cols="12" class="d-flex">
            <v-avatar v-if="contentData.users.name !== '' && contentData.users.avatar == '/'" color="#7879ff" size="48">
                <span class="white--text headline">{{contentData.users.name[0]}}</span>
            </v-avatar>
            <v-avatar v-else
              size="48"
            >
              <v-img :src="contentData.users.avatar"></v-img>
            </v-avatar>
            <div class="ml-2 d-flex flex-column">
              <p class="mb-0 font-size-0-95 font-weight-bold mb-auto primary-font-color"> {{lang.questionnaire}}  </p>
              <p class="mb-0 font-size-0-8"><span class="font-color-gray">{{TimeViewMD(contentData.created_at)}}  </span> {{contentData.users.name}}</p>
            </div>
          </v-col>
        </v-row>
        <div v-if="answerUserShow == false">
          <v-row class="ma-0 px-5 px-md-10 mt-5">
              <v-col cols="12" class="d-flex justify-center align-center">
                  <h2>{{contentData.questionnaires.title}}</h2>
              </v-col>
              <v-col cols="12">
                  <p>{{contentData.questionnaires.desc}}</p>
              </v-col>
          </v-row>
          <!----questionnaires---->
          <v-row class="ma-0 px-5 px-md-10">
              <v-col cols="12" v-for="(content, index) in contentData.questionnaires.content" :key="index">
                  <!--  single Datas  -->
                  <v-row v-if="content.type == 'single'">
                    <v-col cols="12" class="hover-cursor-point">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>单选题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.singleContentDataArr[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.singleContentDataArr[0])">
                      <AttachItemViewer :items="content.singleContentDataArr[0]" />
                    </v-col>
                    <v-col class="pl-6 hover-cursor-point" cols="12" v-for="(singleData, singleDataIndex) in content.singleContentDataArr" :key="`${index}_${singleDataIndex}`" v-if="singleDataIndex !== 0">
                      <div class="d-flex align-center hover-cursor-point" @click="singleAnswer(`${index}_${singleDataIndex}`,index,content.type)" :class="{active: answerData.indexOf(`${index}_${singleDataIndex}`) > -1}"> 
                        <v-chip
                          class="mr-2"
                          :color="answerData.indexOf(`${index}_${singleDataIndex}`) > -1 ? '#49d29e': '#999999'"
                          :outlined="!(answerData.indexOf(`${index}_${singleDataIndex}`) > -1)"
                          :dark="answerData.indexOf(`${index}_${singleDataIndex}`) > -1"
                        >
                          <strong>{{alphabet[singleDataIndex-1]}}</strong>
                        </v-chip>
                        <p class="mb-0 text-wrap width-100-without-68-px"> {{singleData.text}}</p>
                      </div>
                      <AttachItemViewer :items="singleData" v-if="checkIfAttachExist(singleData)" />
                    </v-col>
                  </v-row>
                  <!--  multi Datas  -->
                  <v-row v-if="content.type == 'multi'">
                    <v-col cols="12" class="hover-cursor-point">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>多选题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.multiContentDataArr[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.multiContentDataArr[0])">
                      <AttachItemViewer :items="content.multiContentDataArr[0]" />
                    </v-col>
                    <v-col class="pl-6 hover-cursor-point" cols="12" v-for="(multiData, multiDataIndex) in content.multiContentDataArr" :key="`${index}_${multiDataIndex}`" v-if="multiDataIndex !== 0">
                      <div class="d-flex align-center hover-cursor-point" @click="multiAnswer(`${index}_${multiDataIndex}`,index,content.type)"  :class="{active: answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1}" v-if="alreadyAnswer == true"> 
                        <v-chip
                          class="mr-2"
                          :color="answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1 ? '#49d29e': '#999999'"
                          :outlined="!(answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1)"
                          :dark="answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1"
                        >
                          <strong>{{alphabet[multiDataIndex-1]}}</strong>
                        </v-chip>
                        <p class="mb-0 text-wrap width-100-without-68-px"> {{multiData.text}}</p>
                      </div>
                      <div class="d-flex align-center hover-cursor-point" @click="multiAnswer(`${index}_${multiDataIndex}`,index,content.type)"  :class="{active: multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1}" v-else>
                        <v-chip
                          class="mr-2"
                          :color="multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1 ? '#49d29e': '#999999'"
                          :outlined="!(multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1)"
                          :dark="multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1"
                        >
                          <strong>{{alphabet[multiDataIndex-1]}}</strong>
                        </v-chip>
                        <p class="mb-0 text-wrap width-100-without-68-px"> {{multiData.text}}</p>
                      </div> 
                      <AttachItemViewer :items="multiData" v-if="checkIfAttachExist(multiData)" />
                    </v-col>
                  </v-row>
                  <!--  qa Datas  -->
                  <v-row v-if="content.type == 'qa'">
                    <v-col cols="12" class="hover-cursor-point">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>问答题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.qaContentDataArr[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.qaContentDataArr[0])">
                      <AttachItemViewer :items="content.qaContentDataArr[0]" />
                    </v-col>
                    <v-col cols="12">
                      <v-textarea
                        clearable
                        solo
                        clear-icon="mdi-close-circle"
                        label=""
                        value=""
                        v-model="answerData[index]"
                        hide-details
                        :disabled="alreadyAnswer"
                      ></v-textarea>
                    </v-col>
                  </v-row>
                  <v-row v-if="content.type == 'score'">
                    <v-col cols="12">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>评分题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.scoringDataArr[0].contentData[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.scoringDataArr[0].contentData[0])">
                      <AttachItemViewer :items="content.scoringDataArr[0].contentData[0]" />
                    </v-col>
                    <v-col cols="12">
                      <div v-for="(minute,i) in parseInt(content.scoringDataArr[0].maxMin)" :key="i" class="op-score" :class="{selMinute: minute == answerData[index]}" @click="selScoring(minute,index)">
                          {{minute}}
                      </div>
                    </v-col>
                  </v-row>
              </v-col>
          </v-row>
          <FooterPost :footerInfo='contentData' @updateFooterInfo='updateFooterInfo'></FooterPost>
          <CommentView class="pb-10"></CommentView>
          <!-- <v-row class="ma-0 position-fixed-bottom-0 w-100 bg-white pa-3 ">
              <v-col cols="12" class="d-flex justify-space-between align-center pa-0">
                  <v-btn color="#7879ff" block :dark="!alreadyAnswer" large :disabled="alreadyAnswer" :loading="isSubmit" @click="submit"> {{lang.submit}} </v-btn>
              </v-col>
          </v-row> -->
        </div>
        <div v-else>
          <router-view :answerUsers="answerDataList"></router-view>
        </div>
      </v-container>
    </v-container>
    <v-container class="pa-0" v-else>
      <v-row class="px-10 z-index-2 banner-custom ma-0">
        <v-col cols="6" md="4" class="d-flex align-center position-relative">
          <a @click="$router.go(-1)" class="float-left">
            <v-icon size="70" class=" left-24p">
                mdi-chevron-left
            </v-icon>
          </a>
        </v-col>
        <v-col cols="6" md="4" class="d-flex align-center justify-start justify-md-center">
          <h2>{{lang.questionnaire}}</h2>
        </v-col>
        <v-col cols="12" md="4" class="d-flex align-center justify-end">
          <v-btn
            dark
            color="#7879ff"
            tile
            @click="answerUsers"
            v-if="answerUserShow == false"
          >
            已答 {{answerDataList.length}}
            <v-icon right>
                mdi-chevron-right
            </v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <div v-if="isLoading == true" class="d-flex justify-center align-center py-16">
          <v-progress-circular
              indeterminate
              color="#7879ff"
          ></v-progress-circular>
      </div>
      <div v-else class="ma-0">
        <!----title---->
        <v-col cols="12" class="d-flex align-center hover-cursor-point mt-5 px-10">
            <v-avatar v-if="contentData.users.name !== '' && contentData.users.avatar == '/'" color="#7879ff" size="60" class="ma-5">
                <span class="white--text headline">{{contentData.users.name[0]}}</span>
            </v-avatar>
            <v-avatar v-else
              class="ma-5"
              size="60"
            >
              <v-img :src="contentData.users.avatar"></v-img>
            </v-avatar>
            <div>
              <p class="font-weight-black fs-15 mb-3"> {{lang.questionnaire}}  </p>
              <div class="d-flex align-center">
                <v-icon medium color="#7879ff" class="mr-2">mdi-clock-outline </v-icon>
                <p class="mb-0 mr-8">{{TimeView(contentData.created_at)}}</p>
                <v-icon medium color="#7879ff" class="mr-2">mdi-account </v-icon>
                <p class="mb-0">{{contentData.users.name}}</p>
              </div>
            </div>
            <div class="ml-auto mr-5" v-if="user.roleId < 3 || contentData.users.id == user.id">
              <v-menu offset-y >
                <template v-slot:activator="{ attrs, on }">
                  <v-btn icon color="#7879ff" v-bind="attrs" v-on="on" >
                    <v-icon size="30">mdi-chevron-down </v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item link v-if="user.roleId < 3" >
                    <v-list-item-title class="px-2" @click="fixTop(contentData)" v-if="contentData.fixTop == null">{{lang.toTop}}</v-list-item-title>
                    <v-list-item-title class="px-2" @click="relaseTop(contentData.id)" v-else>{{lang.toRelase}}</v-list-item-title>
                  </v-list-item>
                  <v-list-item link >
                    <v-list-item-title class="px-2" @click="postRemove(contentData)">{{lang.remove}}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
        </v-col>
        <div v-if="answerUserShow == false" class="px-10">
          <v-row class="ma-0 px-10 mt-5">
              <v-col cols="12" class="d-flex justify-center align-center">
                  <h2>{{contentData.questionnaires.title}}</h2>
              </v-col>
              <v-col cols="12">
                  <p>{{contentData.questionnaires.desc}}</p>
              </v-col>
          </v-row>
          <!----questionnaires---->
          <v-row class="ma-0 px-10">
              <v-col cols="12" v-for="(content, index) in contentData.questionnaires.content" :key="index">
                  <!--  single Datas  -->
                  <v-row v-if="content.type == 'single'">
                    <v-col cols="12" class="hover-cursor-point">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>单选题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.singleContentDataArr[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.singleContentDataArr[0])">
                      <AttachItemViewer :items="content.singleContentDataArr[0]" />
                    </v-col>
                    <v-col class="pl-6 hover-cursor-point" cols="12" v-for="(singleData, singleDataIndex) in content.singleContentDataArr" :key="`${index}_${singleDataIndex}`" v-if="singleDataIndex !== 0">
                      <div class="d-flex align-center hover-cursor-point" @click="singleAnswer(`${index}_${singleDataIndex}`,index,content.type)" :class="{active: answerData.indexOf(`${index}_${singleDataIndex}`) > -1}"> 
                        <v-chip
                          class="mr-2"
                          :color="answerData.indexOf(`${index}_${singleDataIndex}`) > -1 ? '#49d29e': '#999999'"
                          :outlined="!(answerData.indexOf(`${index}_${singleDataIndex}`) > -1)"
                          :dark="answerData.indexOf(`${index}_${singleDataIndex}`) > -1"
                        >
                          <strong>{{alphabet[singleDataIndex-1]}}</strong>
                        </v-chip>
                        <p class="mb-0 text-wrap width-100-without-68-px"> {{singleData.text}}</p>
                      </div>
                      <!-- <div>100%</div> -->
                      <AttachItemViewer :items="singleData" v-if="checkIfAttachExist(singleData)" />
                    </v-col>
                  </v-row>
                  <!--  multi Datas  -->
                  <v-row v-if="content.type == 'multi'">
                    <v-col cols="12" class="hover-cursor-point">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>多选题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.multiContentDataArr[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.multiContentDataArr[0])">
                      <AttachItemViewer :items="content.multiContentDataArr[0]" />
                    </v-col>
                    <v-col class="pl-6 hover-cursor-point" cols="12" v-for="(multiData, multiDataIndex) in content.multiContentDataArr" :key="`${index}_${multiDataIndex}`" v-if="multiDataIndex !== 0">
                      <div class="d-flex align-center hover-cursor-point" @click="multiAnswer(`${index}_${multiDataIndex}`,index,content.type)"  :class="{active: answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1}" v-if="alreadyAnswer == true"> 
                        <v-chip
                          class="mr-2"
                          :color="answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1 ? '#49d29e': '#999999'"
                          :outlined="!(answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1)"
                          :dark="answerData[index].indexOf(`${index}_${multiDataIndex}`) > -1"
                        >
                          <strong>{{alphabet[multiDataIndex-1]}}</strong>
                        </v-chip>
                        <p class="mb-0 text-wrap width-100-without-68-px"> {{multiData.text}}</p>
                      </div>
                      <div class="d-flex align-center hover-cursor-point" @click="multiAnswer(`${index}_${multiDataIndex}`,index,content.type)"  :class="{active: multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1}" v-else>
                        <v-chip
                          class="mr-2"
                          :color="multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1 ? '#49d29e': '#999999'"
                          :outlined="!(multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1)"
                          :dark="multiAnswerArr.indexOf(`${index}_${multiDataIndex}`) > -1"
                        >
                          <strong>{{alphabet[multiDataIndex-1]}}</strong>
                        </v-chip>
                        <p class="mb-0 text-wrap width-100-without-68-px"> {{multiData.text}}</p>
                      </div> 
                      <AttachItemViewer :items="multiData" v-if="checkIfAttachExist(multiData)" />
                    </v-col>
                  </v-row>
                  <!--  qa Datas  -->
                  <v-row v-if="content.type == 'qa'">
                    <v-col cols="12" class="hover-cursor-point">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>问答题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.qaContentDataArr[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.qaContentDataArr[0])">
                      <AttachItemViewer :items="content.qaContentDataArr[0]" />
                    </v-col>
                    <v-col cols="12">
                      <v-textarea
                        clearable
                        solo
                        clear-icon="mdi-close-circle"
                        label=""
                        value=""
                        v-model="answerData[index]"
                        hide-details
                        :disabled="alreadyAnswer"
                      ></v-textarea>
                    </v-col>
                  </v-row>
                  <v-row v-if="content.type == 'score'">
                    <v-col cols="12">
                      <p class="mb-0 d-flex align-center"> 
                        {{index + 1}}.  
                        <v-chip class="ma-2" color="success" outlined >
                          <strong>评分题</strong>
                        </v-chip>
                      </p>
                      <p class="text-wrap pl-3 mb-0">{{ content.scoringDataArr[0].contentData[0].text }}</p>
                    </v-col>
                    <v-col v-if="checkIfAttachExist(content.scoringDataArr[0].contentData[0])">
                      <AttachItemViewer :items="content.scoringDataArr[0].contentData[0]" />
                    </v-col>
                    <v-col cols="12">
                      <div v-for="(minute,i) in parseInt(content.scoringDataArr[0].maxMin)" :key="i" class="op-score" :class="{selMinute: minute == answerData[index]}" @click="selScoring(minute,index)">
                          {{minute}}
                      </div>
                    </v-col>
                  </v-row>
              </v-col>
          </v-row>
          <v-row class="d-flex justify-end px-10 mx-0 my-10">
            <v-btn
                  dark
                  color="#7879ff"
                  tile
                  :loading="isSubmit"
                  :disabled="alreadyAnswer"
                  @click="submit"
              > 
                  {{lang.submit}}
              </v-btn>
          </v-row>
          <FooterPost :footerInfo='contentData' @updateFooterInfo='updateFooterInfo'></FooterPost>
          <CommentView></CommentView>
        </div>
        <div v-else>
          <router-view :answerUsers="answerDataList"></router-view>
        </div>
      </div>
    </v-container>
</template>

<script>
import {mapGetters} from 'vuex';
import lang from '~/helper/lang.json';
import AttachItemViewer from '~/components/attachItemViewer';
import {createAnswerQuestionnaire,getAnswerQuestionnaire} from '~/api/postAnswer';
import FooterPost from '~/components/contents/footerPost'
import CommentView from '~/pages/school/posts/comments/commentView';
export default {
    components:{
        AttachItemViewer,
        FooterPost,
        CommentView
    },

    data:()=>({
        baseUrl:window.Laravel.base_url,
        lang,
        alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                'O','P','Q','R','S','T','U','V','W','X','Y','Z' ],
        // answerData:{
        //   singleAnswer:[],
        //   multiAnswer:[],
        //   questionAnswer:[],
        //   statAnswer:[],
        //   scoringAnswer:[],
        //   postId:null
        // },
        answerData:[],
        checkCnt:0,
        multiAnswerArr:[],
        answerDataList:[],
        isSubmit:false,
        alreadyAnswer:false,
        answerUserShow:false,
        isLoading:false
    }),

    computed:{
        currentpath(){
            return this.$route;
        },
        ...mapGetters({
            contentData:'content/postDetail',
            user:'auth/user'
        })
    },
    watch:{
      currentpath:{
        handler(val){
          if(val.name == 'details.quesionnaire' || val.name == 'details.classQuesionnaire'){
            this.answerUserShow = false
          }
        },
        deep:true
      }
    },
    async created(){
        if(this.contentData == null){
          if(this.currentpath.params.lessonId){
            this.$router.push({name:'classSpace.news'})
          }else{
            this.$router.push({name:'schoolSpace.news'})
          }
        }
        this.isLoading = true
        this.contentData.questionnaires.content = JSON.parse(this.contentData.questionnaires.content);
        await getAnswerQuestionnaire({postId:this.contentData.id}).then(res=>{
          this.answerDataList = res.data;
          this.answerDataList.map(answerData=>{
            if(answerData.userId == this.user.id){
              this.answerData = JSON.parse(answerData.answerData)
              // console.log(this.answerData)
              this.alreadyAnswer = true
            }
            
          })
          this.isLoading = false
        }).catch(err=>{
          this.isLoading = false
          console.log(err.response)
        })
        console.log('========',this.contentData)
        console.log('--------',this.answerDataList)
    },

    methods:{
        checkIfAttachExist(data){
            let count = 0;
            count = count + data.imgUrl.length + data.videoUrl.length + data.otherUrl.length;
            if( count == 0 ) {
                return false;
            }
            else{
                return true;
            }
        },
        answerUsers(){
          //console.log('answerUsers')
          console.log(this.answerDataList)
          if(this.answerDataList.length == 0){
            return this.$snackbar.showMessage({content: this.lang.noData, color: 'error'})
          }
          this.answerUserShow = true
          this.$router.push({name:'details.questionnaireUsers'})
        },
        singleAnswer(key,index,type){
          if(this.alreadyAnswer == true){
            return this.$snackbar.showMessage({content: '您已经回答了该帖子', color: 'error'})
          }
          this.$set(this.answerData,index,key)
        },
        multiAnswer(key,index,type){
          if(this.alreadyAnswer == true){
            return this.$snackbar.showMessage({content: '您已经回答了该帖子', color: 'error'})
          }
          let idx = this.multiAnswerArr.indexOf(key)
          if(idx > -1){
            this.multiAnswerArr.splice(idx,1)
          }else{
            this.multiAnswerArr.push(key)
          }
          this.$set(this.answerData,index,this.multiAnswerArr)
          
        },
        selScoring(minute,index){
          if(this.alreadyAnswer == true){
            return this.$snackbar.showMessage({content: '您已经回答了该帖子', color: 'error'})
          }
          // this.answerData[index] = minute
          this.$set(this.answerData,index,minute)
        },
        async submit(){
          if(this.alreadyAnswer == true){
            return this.$snackbar.showMessage({content: '您已经回答了该帖子', color: 'error'})
          }

          if(this.contentData.questionnaires.deadline < this.TimeView(Date.now())){
            return this.$snackbar.showMessage({content: this.lang.deadlinePassed, color: 'error'})
          }
          
          this.answerData.map(answer=>{
            this.checkCnt ++;
          })
          if(this.checkCnt != this.contentData.questionnaires.content.length){
            return this.$snackbar.showMessage({content: '请回答所有问题', color: 'error'})
          }
          this.isSubmit = true;
          await createAnswerQuestionnaire({answerData:this.answerData,schoolId:this.currentpath.params.schoolId,lessonId:this.currentpath.params.lessonId,postId:this.contentData.id}).then(res=>{
            //console.log(res)
            if(this.$isMobile()){
              this.$router.push({name: "home"})
            }
            else{
              if(this.currentpath.params.lessonId){
                this.$router.push({name:'classSpace.news'})
              }else{
                this.$router.push({name:'schoolSpace.news'})
              }
            }
            this.isSubmit = false;
          }).catch(err=>{
            this.isSubmit = false;
            console.log(err.response)
          })

        },
        updateFooterInfo(){
          
        }
    }
}
</script>

<style>
  .active{
    /* background-color:#2d8cf0 !important;
    cursor: pointer;
    color:white !important; */
  }
</style>