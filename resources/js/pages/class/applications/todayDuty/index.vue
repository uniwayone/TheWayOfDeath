<template>
  <v-container class="pa-0">
    <v-container
      class="banner-custom z-index-2"
      color="white"
      sticky
      elevation="20"
    >
      <v-row class="ma-0">
        <v-col cols="12" class="justify-space-between d-flex ma-0 align-center">
          <a @click="$router.go(-1)">
            <v-icon size="70">
              mdi-chevron-left
            </v-icon>
          </a>
          <h2>今日值日</h2>
          <v-btn
            tile
            color="#7879ff"
            dark
            class="mr-5"
            @click="post"
            v-if="user.roleId != 6"
          >
            <v-icon left>
              mdi-book-plus
            </v-icon>
            发布
          </v-btn>
          <span v-else> </span>
        </v-col>
      </v-row>
    </v-container>
    <!-- <v-divider class="thick-border"></v-divider> -->
    <v-container
      v-if="contentList.length && content.todayduty"
      class="px-5"
      v-for="content in contentList"
      :key="content.id"
    >
      <v-row class="pa-0 mt-1 ma-0">
        <TodayDutyPost :content="content"></TodayDutyPost>
        <FooterPost :footerInfo="content"></FooterPost>
      </v-row>
    </v-container>
    <InfiniteLoading class="pb-3 w-100" @infinite="infiniteHandler">
      <div slot="spinner">
        <v-row class="pa-3">
          <v-col cols="12" class="pt-10">
            <v-skeleton-loader
              v-bind="attrs"
              type=" list-item-avatar-two-line, list-item-three-line,list-item,list-item-two-line, actions"
              :loading="isLoadingContents"
            ></v-skeleton-loader>
          </v-col>
          <v-divider></v-divider>
          <v-col cols="12" class="pt-10">
            <v-skeleton-loader
              v-bind="attrs"
              type=" list-item-avatar-two-line, list-item-three-line,list-item,list-item-two-line, actions"
              :loading="isLoadingContents"
            ></v-skeleton-loader>
          </v-col>
          <v-divider></v-divider>
          <v-col cols="12" class="pt-10">
            <v-skeleton-loader
              v-bind="attrs"
              type=" list-item-avatar-two-line, list-item-three-line,list-item,list-item-two-line, actions"
              :loading="isLoadingContents"
            ></v-skeleton-loader>
          </v-col>
          <v-divider></v-divider>
          <v-col cols="12" class="pt-10">
            <v-skeleton-loader
              v-bind="attrs"
              type=" list-item-avatar-two-line, list-item-three-line,list-item,list-item-two-line, actions"
              :loading="isLoadingContents"
            ></v-skeleton-loader>
          </v-col>
          <v-divider></v-divider>
          <v-col cols="12" class="pt-10">
            <v-skeleton-loader
              v-bind="attrs"
              type=" list-item-avatar-two-line, list-item-three-line,list-item,list-item-two-line, actions"
              :loading="isLoadingContents"
            ></v-skeleton-loader>
          </v-col>
          <v-divider></v-divider>
          <v-col cols="12" class="pt-10">
            <v-skeleton-loader
              v-bind="attrs"
              type=" list-item-avatar-two-line, list-item-three-line,list-item,list-item-two-line, actions"
              :loading="isLoadingContents"
            ></v-skeleton-loader>
          </v-col>
          <v-divider></v-divider>
        </v-row>
      </div>
      <div slot="no-more" class="pa-3 ma-3 text-center">
        <v-chip class="ma-2" color="#7879ff" outlined pill>
          暂无
          <v-icon right>
            mdi-cancel
          </v-icon>
        </v-chip>
      </div>
      <div
        slot="no-results"
        class="position-relative row ma-0 p-2 h-50 d-flex justify-content-center align-items-center"
      >
        <div class="w-100 text-center p-5 m-5 mt-10">
          <v-icon size="150" color="grey darken-1">
            mdi-magnify
          </v-icon>
          <h5>暂无</h5>
        </div>
      </div>
    </InfiniteLoading>
  </v-container>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";
import { getTodayDutyData } from "~/api/todayDuty";
import TodayDutyPost from "~/components/contents/todayDutyPost";
import FooterPost from "~/components/contents/footerPost";
import lang from "~/helper/lang.json";
import { mapGetters } from "vuex";
export default {
  components: {
    InfiniteLoading,
    TodayDutyPost,
    FooterPost
  },
  data: () => ({
    isLoadingContents: false,
    attrs: {
      class: "mb-6"
    },
    pageOfContent: 1,
    lastPageOfContent: 0,
    contentList: [],
    baseUrl: window.Laravel.base_url,
    lang
  }),
  computed: {
    currentPath() {
      return this.$route;
    },
    ...mapGetters({
      user: "auth/user"
    })
  },
  watch: {
    currentPath: {
      handler(val) {
        if (val.query.fix) {
          let index = this.contentList.findIndex(
            content => content.id == this.currentPath.query.fix
          );
          if (index > -1) {
            let fixVal = this.contentList[index];
            this.contentList.splice(index, 1);
            let currentTime = this.TimeView(Date.now());
            fixVal.fixTop = currentTime;
            this.contentList.unshift(fixVal);
          }
        }
        if (val.query.release) {
          let index = this.contentList.findIndex(
            content => content.id == this.currentPath.query.release
          );
          if (index > -1) {
            let releaseVal = this.contentList[index];
            releaseVal.fixTop = null;
            this.contentList.splice(index, 1);
            this.contentList.push(releaseVal);
          }
        }
        if (val.query.remove) {
          let index = this.contentList.findIndex(
            content => content.id == this.currentPath.query.remove
          );
          if (index > -1) {
            this.contentList.splice(index, 1);
          }
        }
      },
      deeper: true
    }
  },
  methods: {
    async infiniteHandler($state) {
      let timeOut = 0;
      this.isLoadingContents = true;
      if (this.pageOfContent > 1) {
        timeOut = 1000;
      }
      let vm = this;
      await getTodayDutyData({
        page: this.pageOfContent,
        schoolId: this.currentPath.params.schoolId,
        lessonId: this.currentPath.params.lessonId
      })
        .then(res => {
          if (vm.pageOfContent == 1 && res.data.data.length == 0) {
            $state.complete();
            return;
          }
          vm.lastpageOfContent = res.data.last_page;
          $.each(res.data.data, function(key, value) {
            console.log("value", value);
            vm.contentList.push(value);
          });
          if (vm.pageOfContent - 1 === vm.lastpageOfContent) {
            $state.complete();
          } else {
            $state.loaded();
          }
          vm.pageOfContent = vm.pageOfContent + 1;
        })
        .catch(err => {
          console.log(err.response);
        });
      this.isLoadingContents = false;
    },
    post() {
      this.$router.push({ name: "posts.CtodayDuty" });
    }
  }
};
</script>

<style></style>
