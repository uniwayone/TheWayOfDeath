<template>
    <v-container v-if="$isMobile()" class="pa-0 h-100 bg-gray-light-dark mb-16 pb-3 pt-12">
        <v-row class="ma-0 bg-white justify-center position-sticky-top-0" >
            <v-icon @click="$router.go(-1)" size="35" class="position-absolute put-align-center" style="left: 0px; top:50%" >
                mdi-chevron-left
            </v-icon>
            <p class="mb-0 font-size-0-95 font-weight-bold pa-3" >多选题</p>
            <v-btn @click="addContent" text color="#7879ff" class="position-absolute put-align-center" style="right: 0px; top:50%">
                {{lang.addOption}}
            </v-btn>
        </v-row>
        <div v-if="isNew == true" v-for="index in initialCnt" :key="index">
            <QuestionItem class="mt-2" :Label="index == 1 ? lang.contentPlaceFirst : `${lang.contentOptionPlace}${index-1}`" :index="index" :ref="index" @contentData="loadContentData"/>
            <v-divider></v-divider>
        </div>
        <div v-if="isNew == false" v-for="(item, index) in multiData.multiContentDataArr" :key="index" >
            <QuestionItem class="mt-2" :item="item" :Label="index == 1 ? lang.contentPlaceFirst : `${lang.contentOptionPlace}${index-1}`" :index="index" :ref="index" @contentData="loadContentData"/>
            <v-divider></v-divider>
        </div>
        <v-row class="ma-0 position-absolute-bottom-0 w-100 bg-white pa-3 ">
            <v-col cols="12" class="d-flex justify-space-between align-center pa-0">
                <v-btn color="#7879ff" block dark large @click="addMultiContent"> 确认 </v-btn>
            </v-col>
        </v-row>
    </v-container>
    <v-container class="pa-0" v-else>
        <v-container class="px-10 z-index-2 banner-custom">
            <v-row>
              <v-col cols="6" md="4" class="d-flex align-center position-relative">
                <a @click="$router.go(-1)">
                    <v-icon size="70" class=" left-24p">
                        mdi-chevron-left
                    </v-icon>
                </a>
              </v-col>
              <v-col cols="6" md="4" class="d-flex align-center justify-start justify-md-center">
                 <h2>多选题</h2>
              </v-col>
              <v-col cols="12" md="4" class="d-flex align-center justify-end">
                <v-btn tile dark color="#F19861" @click="addContent" >
                    <v-icon> mdi-plus </v-icon>
                    {{lang.addOption}}
                </v-btn>
                <v-btn tile dark color="#7879ff" class="mx-2" @click="addMultiContent" >
                    {{lang.submit}}
                </v-btn>
              </v-col>
            </v-row>
        </v-container>
        <!-- <div v-for="index in initialCnt" :key="index" class="mt-3 px-10">
            <QuestionItem class="mt-10" :Label="index == 1 ? lang.contentPlaceFirst : `${lang.contentOptionPlace}${index-1}`" :index="index" :ref="index" @contentData="loadContentData"/>
            <v-divider></v-divider>
        </div> -->
        <div v-if="isNew == true" v-for="index in initialCnt" :key="index" class="mt-3 px-10">
            <QuestionItem class="mt-10" :Label="index == 1 ? lang.contentPlaceFirst : `${lang.contentOptionPlace}${index-1}`" :index="index" :ref="index" @contentData="loadContentData"/>
            <v-divider></v-divider>
        </div>
        
        <div v-if="isNew == false" v-for="(item, index) in multiData.multiContentDataArr" :key="index" class="mt-3 px-10">
            <QuestionItem class="mt-10" :item="item" :Label="index == 1 ? lang.contentPlaceFirst : `${lang.contentOptionPlace}${index-1}`" :index="index" :ref="index" @contentData="loadContentData"/>
            <v-divider></v-divider>
        </div>
        <v-snackbar
            timeout="3000"
            v-model="requiredText"
            color="error"
            absolute
            top
            >
            {{lang.requiredText}}
        </v-snackbar>
    </v-container>
</template>

<script>
import QuestionItem from '~/components/questionItem';
import { Fragment } from 'vue-fragment';
import lang from '~/helper/lang.json';
export default {
    components:{
        QuestionItem,
        Fragment,
    },
    props:{
        type:{
            type:String,
            required:false
        }
    },

    data: () =>({
        multiData : {
            type : 'multi',
            multiContentDataArr:[],
            index: null,
        },
        initialCnt:4,
        lang,
        requiredText:false,
        isNew:true,
    }),

    computed:{
        currentPath(){
            return this.$route
        }
    },

    created(){
        console.log(this.currentPath);
        if(this.currentPath.params.editDataArr !== undefined){
            this.isNew = false;
            this.multiData.multiContentDataArr = this.currentPath.params.editDataArr;
            this.multiData.index = this.currentPath.params.editDataIndex;
        }
        console.log('----------------------',this.type)
        if(this.type == undefined){
            this.$router.push({name:'posts.questionnaire'})
        }
    },

    methods:{
        addContent(){
            if(this.isNew == true){
                this.initialCnt ++;
            }
            else if (this.isNew == false){
                let contentData = {
                    imgUrl: [],
                    otherUrl: [],
                    text: '',
                    videoUrl: [],
                }
                this.multiData.multiContentDataArr.push(contentData)
            }
        },
        addMultiContent(){
            // for(let index = 1;  index <= this.initialCnt; index++){
            //     this.$refs[index][0].emitData()
            // }
             if(this.isNew == false){
                let initialLength = this.multiData.multiContentDataArr.length;
                for(let index = 0;  index < initialLength; index++){
                    this.$refs[index][0].emitData()
                }
                this.multiData.multiContentDataArr = this.multiData.multiContentDataArr.splice(0, initialLength );
            }
            else{
                for(let index = 1;  index <= this.initialCnt; index++){
                    this.$refs[index][0].emitData()
                }
            }
            if(this.multiData.multiContentDataArr.length<4){
                return
            }
            
            this.$emit('contentData',this.multiData);
            if(this.type == 'post'){
                this.$router.push({name:'posts.questionnaire'});
            }else{
                this.$router.push({name:'questionnaire.templateNew'})
            }
        },
        loadContentData(data){
            if(data.text === ''){
                return this.$snackbar.showMessage({content: "标题不能为空", color: "error"})
            }
            this.multiData.multiContentDataArr.push(data)
        }

    }
}
</script>

<style>

</style>