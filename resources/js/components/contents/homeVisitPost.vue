<template>
  <v-container v-if="$isMobile()">
    <v-row>
      <v-col
        cols="12"
        class="d-flex hover-cursor-point align-center"
        @click="showDetail"
      >
        <v-avatar>
          <v-img
            :src="`${baseUrl}/asset/img/appIcon/others/家访.png`"
            alt="postItem"
          ></v-img>
        </v-avatar>
        <div class="ml-2 d-flex flex-column">
          <p
            class="mb-0 font-size-0-95 font-weight-bold mb-auto primary-font-color"
          >
            {{ lang.homeVisit }}
          </p>
          <p class="mb-0 font-size-0-8">
            <span class="font-color-gray"
              >{{ TimeViewMD(content.created_at) }}
            </span>
            {{ content.users.name }}
          </p>
        </div>
      </v-col>
      <v-col cols="12" class="py-0 font-size-0-8">
        <div class="d-flex align-center">
          <p class="text-wrap mb-0">
            <strong>截止日期:</strong>
            {{ TimeView(homeVisitData.deadline) }}
          </p>
        </div>
        <div class="d-flex align-center">
          <p class="text-wrap mb-0">
            <strong>家访内容:</strong>
            {{ homeVisitData.type }}
          </p>
        </div>
      </v-col>
      <v-col cols="12" class="pb-0">
        <p class="text-wrap mb-0 font-size-0-75">
          <read-more
            more-str="全文"
            :text="homeVisitData.content.text"
            link="#"
            less-str="收起"
            :max-chars="250"
          ></read-more>
        </p>
      </v-col>
      <v-col
        cols="12"
        class="py-0"
        v-if="checkIfAttachExist(homeVisitData.content)"
      >
        <AttachItemViewer :items="homeVisitData.content" />
      </v-col>
    </v-row>
  </v-container>
  <v-container v-else>
    <v-col
      cols="12"
      class="d-flex align-center hover-cursor-point"
      @click="showDetail(content)"
    >
      <v-avatar class="ma-3 school-card-avatar" tile>
        <v-img
          :src="`${baseUrl}/asset/img/newIcon/家访.png`"
          alt="postItem"
        ></v-img>
      </v-avatar>
      <div>
        <p class="font-weight-black fs-15 mb-3">{{ lang.homeVisit }}</p>
        <div class="d-flex align-center">
          <v-icon medium color="#7879ff" class="mr-2"
            >mdi-clock-outline
          </v-icon>
          <p class="mb-0 mr-8">{{ TimeView(content.created_at) }}</p>
          <v-icon medium color="#7879ff" class="mr-2">mdi-account </v-icon>
          <p class="mb-0">{{ content.users.name }}</p>
        </div>
      </div>
      <div class="ml-auto">
        <v-menu offset-y>
          <template v-slot:activator="{ attrs, on }">
            <v-btn icon color="#7879ff" v-bind="attrs" v-on="on">
              <v-icon size="30">mdi-chevron-down </v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item link>
              <v-list-item-title
                class="px-2"
                @click="fixTop(content)"
                v-if="content.fixTop == null"
                >{{ lang.toTop }}</v-list-item-title
              >
              <v-list-item-title
                class="px-2"
                @click="relaseTop(content.id)"
                v-else
                >{{ lang.toRelase }}</v-list-item-title
              >
            </v-list-item>
            <v-list-item link>
              <v-list-item-title class="px-2" @click="postRemove(content)">{{
                lang.remove
              }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-col>
    <v-col cols="12" class="pl-10 pt-0">
      <div class="d-flex align-center">
        <p class="text-wrap mb-0">
          <strong>截止日期:</strong>
          {{ TimeView(homeVisitData.deadline) }}
        </p>
      </div>
      <div class="d-flex align-center">
        <p class="text-wrap mb-0">
          <strong>家访内容:</strong>
          {{ homeVisitData.type }}
        </p>
      </div>
    </v-col>
    <v-col cols="12" class="pl-10 pt-0">
      <v-row>
        <v-col cols="12">
          <p class="text-wrap">
            <read-more
              more-str="全文"
              :text="homeVisitData.content.text"
              link="#"
              less-str="收起"
              :max-chars="250"
            ></read-more>
          </p>
        </v-col>
        <v-col cols="12" v-if="checkIfAttachExist(homeVisitData.content)">
          <AttachItemViewer :items="homeVisitData.content" />
        </v-col>
      </v-row>
    </v-col>
  </v-container>
</template>

<script>
import AttachItemViewer from "~/components/attachItemViewer";
import { createReadCnt } from "~/api/alarm";
import lang from "~/helper/lang.json";
export default {
  components: {
    AttachItemViewer
  },
  props: {
    content: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    lang,
    baseUrl: window.Laravel.base_url,
    homeVisitData: null
  }),
  computed: {
    currentPath() {
      return this.$route;
    }
  },
  created() {
    this.homeVisitData = this.content.home_visit;
    this.homeVisitData.content = JSON.parse(this.homeVisitData.content);
    this.homeVisitData.description = JSON.parse(this.homeVisitData.description);
  },
  methods: {
    showDetail(content) {
      createReadCnt({ postId: content.id })
        .then(res => {})
        .catch(err => {
          console.log(err.response);
        });
      this.$store.dispatch("content/storePostDetail", content);
      if (this.currentPath.params.lessonId) {
        this.$router.push({ name: "details.classDefault" });
      } else {
        this.$router.push({ name: "details.schoolDefault" });
      }
    }
  }
};
</script>

<style></style>
