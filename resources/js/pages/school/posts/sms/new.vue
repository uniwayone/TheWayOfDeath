<template>
    <v-container v-if="$isMobile()" class="ma-0 pa-0 h-100">
        <v-container class="pa-0 h-100 bg-white mb-16 pb-3 pt-12" >
            <v-row class="ma-0 bg-white justify-center position-sticky-top-0" >
                <v-icon @click="$router.go(-1)" size="35" class="position-absolute put-align-center" style="left: 0px; top:50%" >
                    mdi-chevron-left
                </v-icon>
                <p class="mb-0 font-size-0-95 font-weight-bold pa-3" >{{lang.sms}}</p>
                <v-btn @click="submit" :loading="isSubmit" text color="#7879ff" class="position-absolute put-align-center" style="right: 0px; top:50%">
                    {{lang.submit}}
                </v-btn>
            </v-row>
            <div class="cus-divider-light-gray-height"></div>
            <v-row class="ma-0 mo-glow mb-16">
                <v-col cols="12">
                    <QuestionItem :Label="lang.contentPlaceFirst" :emoji="true" ref="child" @contentData="loadContentData"></QuestionItem>
                </v-col>
            </v-row>
            <v-snackbar
                timeout="3000"
                v-model="requiredText"
                color="error"
                absolute
                top
                >
                {{lang.requiredText}}
            </v-snackbar>
            <v-snackbar
            timeout="3000"
                v-model="isSuccessed"
                color="success"
                absolute
                top
                >
                {{lang.successText}}
            </v-snackbar>
        </v-container>
  </v-container>
    <v-container class="pa-0" v-else>
        <v-container class="px-10 z-index-2 banner-custom">
            <v-row>
                <v-col cols="6" md="4" class="d-flex align-center position-relative">
                    <a @click="$router.go(-1)">
                        <v-icon size="70" class="left-24p">
                            mdi-chevron-left
                        </v-icon>
                    </a>
                </v-col>
                <v-col cols="6" md="4" class="d-flex align-center justify-start justify-md-center">
                    <h2>{{lang.sms}}</h2>
                </v-col>
                <v-col cols="12" md="4" class="d-flex align-center justify-end">
                    <v-btn
                        text
                        color="#999999"
                        @click="selContent('template')"
                    >
                        可用模板 0， 草稿 0
                    </v-btn>
                    <v-btn
                        dark
                        tile
                        color="#F19861"
                        class=""
                        :loading="isDraft"
                        @click="saveDraft"
                    >
                        {{lang.saveDraft}}
                    </v-btn>
                    <v-btn
                        dark
                        tile
                        color="#7879ff"
                        class="mx-2"
                        :loading="isSubmit"
                        @click="submit"
                    >
                        {{lang.submit}}
                    </v-btn>
                    
                </v-col>
            </v-row>
        </v-container>
        <v-container class="pa-10">
            <QuestionItem :Label="lang.contentPlaceFirst" :emoji="true" ref="child" @contentData="loadContentData"></QuestionItem>
        </v-container>
        <v-snackbar
            timeout="3000"
            v-model="requiredText"
            color="error"
            absolute
            top
            >
            {{lang.requiredText}}
        </v-snackbar>
        <v-snackbar
        timeout="3000"
            v-model="isSuccessed"
            color="success"
            absolute
            top
            >
            {{lang.successText}}
        </v-snackbar>
  </v-container>
</template>

<script>
import lang from '~/helper/lang.json'
import QuestionItem from '~/components/questionItem'
import {createSms} from '~/api/sms'
import quickMenu from '~/components/quickMenu'
export default {
    middleware:['auth','post'],
    components:{
        QuestionItem,
        quickMenu,
    },

    data: ()=> ({
        lang,
        baseUrl: window.Laravel.base_url,
        isSubmit:false,
        isDraft:false,
        requiredText:false,
        smsData:{
            schoolId:null,
            content:[]
        },
        isSuccessed:false,
    }),
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        this.smsData.schoolId = this.currentPath.params.schoolId
    },
    methods:{
        saveDraft(){

        },

        async submit(){
            this.$refs.child.emitData()
            if(this.smsData.content.length == 0){
                return
            }
            //console.log(this.smsData)
            this.isSubmit = true
            await createSms(this.smsData).then(res=>{
                //console.log(res)
                this.isSubmit = false
                if(this.$isMobile()){
                    this.$router.push({name:'home'})
                }
                else{
                    this.$router.push({name:'schoolSpace.news'})
                }
            }).catch(err=>{
                //console.log(err.response)
                this.isSubmit = false
            })
        },

        loadContentData(data){
            if(data.text === ''){
                this.smsData.content = [];
                return this.$snackbar.showMessage({content: this.lang.requiredText, color: "error"})
            }
            this.smsData.content.push(data)
        },

        something(){
            
        }
    }
}
</script>

<style>

</style>