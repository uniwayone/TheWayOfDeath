<template>
    <v-container class="ma-0 pa-0 h-100" v-if="$isMobile()" >
        <v-container class="pt-0 px-0 h-100 bg-white pt-12">
            <v-row class="ma-0 bg-white justify-center position-sticky-top-0" >
                <v-icon @click="$router.go(-1)" size="35" class="position-absolute put-align-center" style="left: 0px; top:50%" >
                    mdi-chevron-left
                </v-icon>
                <p class="mb-0 font-size-0-95 font-weight-bold pa-3" >{{lang.notification}}</p>
            </v-row>
            <div class="cus-divider-light-gray-height"></div>
            <v-row class="ma-0">
                <v-col cols="12" class="">
                    <p class="text-wrap mb-0">
                        <strong>标题:</strong>
                        {{contentData.notifications.title}}
                    </p>
                    <p class="text-wrap mb-0">
                        <strong>公告标题:</strong>
                        {{contentData.notifications.signName}}
                    </p>
                    <p class="text-wrap mb-0">
                        <strong>描述:</strong>
                        {{attachItem.text}}
                    </p>
                    <v-col cols="12" v-if="checkIfAttachExist(attachItem)">
                        <AttachItemViewer :items="attachItem" />
                    </v-col>
                </v-col>
            </v-row>
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
                    <h2>{{lang.notification}}</h2>
                </v-col>
                <v-col cols="12" md="4" class="d-flex align-center justify-end">
                    <!-- <v-btn
                        text
                        color="#7879ff"
                        @click="templateList"
                    >
                        可用模板 0， 草稿 0
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
                    <v-btn
                        dark
                        tile
                        color="#F19861"
                        :loading="isDraft"
                        @click="saveDraft"
                    >
                        {{lang.saveDraft}}
                    </v-btn> -->
                </v-col>
            </v-row>
        </v-container>
        <v-col cols="12" class="pl-10 pt-0 mt-5">
            <p class="text-wrap mb-0">
                <strong>标题:</strong>
                {{contentData.notifications.title}}
            </p>
            <p class="text-wrap mb-0">
                <strong>公告标题:</strong>
                {{contentData.notifications.signName}}
            </p>
            <p class="text-wrap mb-0">
                <strong>描述:</strong>
                {{attachItem.text}}
            </p>
            <v-col cols="12" v-if="checkIfAttachExist(attachItem)">
                <AttachItemViewer :items="attachItem" />
            </v-col>
        </v-col>
    </v-container>
</template>

<script>
import {mapGetters} from 'vuex';
import lang from '~/helper/lang.json';
import AttachItemViewer from '~/components/attachItemViewer';
export default {
    components:{
        AttachItemViewer,
    },
    data:()=>({
        lang,
        baseUrl:window.Laravel.base_url,
        attachItem:null
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
    created(){
        if(this.contentData == null){
          if(this.currentpath.params.lessonId){
            this.$router.push({name:'classSpace.news'})
          }else{
            this.$router.push({name:'schoolSpace.news'})
          }
        }
        console.log(this.contentData)
        this.attachItem = JSON.parse(this.contentData.notifications.description)
    }
}
</script>

<style>

</style>