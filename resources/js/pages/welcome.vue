<template>
  <v-container fluid class="login-bg d-flex justify-center align-center py-0">

    <v-row v-if="$isMobile()" class="w-100 h-100 bg-white align-center justify-center">
      <v-col cols="12">
        <div class="d-flex justify-center mb-10">
          <img :src="`${baseUrl}/asset/img/logoNewWhite.png`" alt="upload-video-icon" class="mo-login-logo"/>
        </div>
         <v-card flat>
          <v-card-text ref="form">
            <v-form v-model="isFormValid" @submit.prevent="login">
              <p class="mb-0">帐号</p>
              <v-text-field
                class="pt-0 mt-0 max-length-11-staff-input-mo"
                color="#7879ff"
                single-line
                v-model="phoneNumber"
                label="帐号"
              ></v-text-field>
              <p class="mb-0">密码</p>
              <v-text-field
                class="pt-0 mt-0"
                color="#7879ff"
                single-line
                v-model="password"
                name="password"
                label="请输入登录密码"
                hint="至少8个字符"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show1 ? 'text' : 'password'"
                @click:append="show1 = !show1"
              ></v-text-field>
              <v-checkbox
                v-model="agreeTerms"
                label="已阅读并同意《用户服务协议》和《隐私》"
                type="checkbox"
                color="#7879ff"
              >
                <template v-slot:label>
                  <div @click.stop="">
                    已阅读并同意
                    <a
                      href="#"
                      style="color: #7879ff"
                      @click.prevent="terms = true"
                    >《用户服务协议》</a>
                    和
                    <a
                      href="#"
                      style="color: #7879ff"
                      @click.prevent="conditions = true"
                    >《隐私》</a>
                  </div>
                </template>
              </v-checkbox>
              <v-btn color="#7879ff" rounded dark large block type="submit" :loading="isLogging">
                立即登录
              </v-btn>
            </v-form>
            <v-row class="justify-end pa-3">
              <v-btn text class="align-right mt-4 ">
                忘记密码?
              </v-btn>
            </v-row>
            <v-row class="justify-center align-center ">
              <v-divider light></v-divider>
              <span class="px-2">  使用第三方账号登录  </span>
              <v-divider light></v-divider>
            </v-row>
            <v-row class=" justify-start align-center pt-1 pb-4">
              <v-img :src="`${baseUrl}/asset/img/wechat.svg`" max-width="45" class="mt-3"></v-img>
            </v-row>
            <v-dialog :overlay-opacity="$isMobile()? '0': '0.4'"  persistent v-model="terms" width="90%" >
              <v-card>
                <v-card-title class="title">
                  Terms
                </v-card-title>
                <v-card-text
                  v-for="n in 5"
                  :key="n"
                >
                  {{ content }}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="#7879ff"
                    @click="terms = false"
                  >
                    Ok
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog :overlay-opacity="$isMobile()? '0': '0.4'" 
              persistent
              v-model="conditions"
              width="90%"
            >
              <v-card>
                <v-card-title class="title">
                  Conditions
                </v-card-title>
                <v-card-text
                  v-for="n in 5"
                  :key="n"
                >
                  {{ content }}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="#7879ff"
                    @click="conditions = false"
                  >
                    Ok
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row v-else class="justify-center align-center">
      <v-col cols="12" lg="4" class="align-center">
        <p class="text-left white--text">教育是什么，往简单方面说，只需一句话，就是养成良好的习惯。</p>
        <p class="text-right white--text">——布鲁纳（教育家）</p>
      </v-col>
      <v-col cols="12" lg="4">
        <v-row class="justify-center">
          <v-col md="7" sm="12" xs="12">
            <v-card>
              <v-tabs
                v-model="tab"
                background-color="#7879ff"
                centered
                dark
                icons-and-text
              >
                <v-tabs-slider></v-tabs-slider>
                <v-tab href="#qr-login">
                  扫码登录
                  <v-icon>mdi-qrcode</v-icon>
                </v-tab>
                <v-tab href="#phone-login">
                  账户登录
                  <v-icon>mdi-account</v-icon>
                </v-tab>
              </v-tabs>
              <v-tabs-items v-model="tab">
                <v-tab-item value='qr-login' >
                  <v-card flat class="pb-8 d-flex justify-center">
                    <qrcode value="http://8.131.231.180/" :options="{ width: 350 }"></qrcode>
                  </v-card>
                </v-tab-item>
                
                <v-tab-item value='phone-login' >
                  <v-card flat>
                    <v-card-text ref="form">
                      <v-form v-model="isFormValid" @submit.prevent="login">
                        <v-text-field
                          v-model="phoneNumber"
                          label="帐号"
                          color="#7879ff"
                          class="max-length-11-staff-input"
                          prepend-inner-icon="mdi-phone"
                          :rules="[rules.required]"
                          :counter="11"
                        ></v-text-field>
                        <v-text-field
                          v-model="password"
                          name="password"
                          label="密码"
                          hint="至少8个字符"
                          counter
                          color="#7879ff"
                          prepend-inner-icon="mdi-key-chain-variant"
                          :rules="[rules.required, rules.min]"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show1 ? 'text' : 'password'"
                          @click:append="show1 = !show1"
                        ></v-text-field>
                        <v-checkbox
                          color="#7879ff"
                          v-model="agreeTerms"
                          label="已阅读并同意《用户服务协议》和《隐私》"
                          type="checkbox"
                          :rules="[rules.required]"
                        >
                          <template v-slot:label>
                            <div @click.stop="">
                              已阅读并同意
                              <a
                                href="#"
                                @click.prevent="terms = true"
                                style="color: #7879ff"
                              >《用户服务协议》</a>
                              和
                              <a
                                href="#"
                                @click.prevent="conditions = true"
                                style="color: #7879ff"
                              >《隐私》</a>
                            </div>
                          </template>
                        </v-checkbox>
                        <v-btn color="#7879ff" :dark="isFormValid" block type="submit" :loading="isLogging" :disabled="!isFormValid">
                          <v-icon left>
                            mdi-login
                          </v-icon> 
                          登录
                        </v-btn>
                      </v-form>
                      <v-row class="justify-end pa-3">
                        <v-btn color="#7879ff" text class="align-right">
                          忘记密码?
                        </v-btn>
                      </v-row>
                      <v-row class="justify-center align-center ">
                        <span>—————</span>
                        <span class="px-2">  使用第三方账号登录  </span>
                        <span>—————</span>
                      </v-row>
                      <v-row class="justify-center align-center pt-1 pb-4">
                        <v-btn color="#7879ff" text>
                          <v-icon left>
                            mdi-wechat
                          </v-icon> 
                          企业微信
                        </v-btn>
                      </v-row>
                      <v-dialog :overlay-opacity="$isMobile()? '0': '0.4'"  persistent v-model="terms" width="70%" >
                        <v-card>
                          <v-card-title class="title">
                            Terms
                          </v-card-title>
                          <v-card-text
                            v-for="n in 5"
                            :key="n"
                          >
                            {{ content }}
                          </v-card-text>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                              text
                              color="#7879ff"
                              @click="terms = false"
                            >
                              Ok
                            </v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                      <v-dialog :overlay-opacity="$isMobile()? '0': '0.4'" 
                        persistent
                        v-model="conditions"
                        width="70%"
                      >
                        <v-card>
                          <v-card-title class="title">
                            Conditions
                          </v-card-title>
                          <v-card-text
                            v-for="n in 5"
                            :key="n"
                          >
                            {{ content }}
                          </v-card-text>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                              text
                              color="#7879ff"
                              @click="conditions = false"
                            >
                              Ok
                            </v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                    </v-card-text>
                  </v-card>
                </v-tab-item>
              </v-tabs-items>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <v-snackbar
      timeout="3000"
      v-model="loginFailed"
      color="error"
      absolute
      top
    >
      Login Failed
    </v-snackbar>
  </v-container>
</template>

<script>

import { mapGetters } from 'vuex';
import { loginApi } from '~/api/auth';

export default {
  middleware: 'guest',
  layout: 'basic',

  metaInfo () {
    return { title: this.$t('home') }
  },

  data: () => ({
    baseUrl:window.Laravel.base_url,
    tab : 'phone-login',
    phoneNumber : '',
    password : '',
    show1 : false,
    agreeTerms : true,
    remember : false,
    rules : {
      required: value => !!value || '必需的。',
      min: v => v.length >= 8 || '最少8个字符',
    },
    isLogging : false,
    isFormValid : false,
    terms : false,
    conditions : false,
    loginFailed : false,
    schoolTree : [],
    chooseableSchoolTree : [],
    content: `用户协议
在此特别提醒您在使用该软件产品（以下简称“软件”）之前 ,请认真阅读本《用户协议》(以下简称“协议”),确保您充分理解本协议中各条款。请您审慎阅读并选择接受或不接受本协议。您同意并点击确认本协议条款后,才能成为软件的正式用户,并享受软件的各类服务。您的登录、使用等行为将视为对本协议的接受,并同意接受本协议各项条款的约束。若您不同意本协议,或对本协议中的条款存在任何疑问,请您立即停止软件用户程序,并可以选择不使用软件服务。本协议条款是软件公司（以下称“我们”）对用户相关政策的许诺,请您务必仔细阅读。

1. 用户信息采集

当您在本网站 /软件进行用户登记，使用本网站/软件的服务，以及参加本网站/软件的活动时，即为您同意本网站/软件将收集您的个人信息，并保存记录。本网站/软件收集的个人信息包括但不限于：姓名、性别、生日、城市、手机电话等。您主动提供的信息越多及越准确，我们就能够更好地为您提供有关服务。

和彩云SDK由和彩云封装提供，接入形态灵活，涵盖个人网盘文件的在线浏览、上传、备份等功能。和彩云以SDK形式接入智慧校园App，是智慧校园APP因服务必要委托的合作活动伙伴，通过电话、短信、邮件等方式，向用户推送本业务关于市场营销、产品推荐、客户服务等相关信息。

2. 用户信息的使用

本网站 /软件收集个人信息的基本目的是为了更好的了解我们的用户，以便向您提供特色服务和个性化服务。个人信息将主要用于以下几个目的： （1）完成您对特定服务或产品的要求。（2）在我们提供服务时,用于身份验证、客户服务、安全防范、诈骗监测、存档和备份用途,确保我们向您提供的产品和服务的安全性。 （3）帮助我们设计新服务,改善我们现有服务。(4)使我们更加了解您如何接入和使用我们的服务,从而针对性地回应您的个性化需求,例如语言设定、位置设定、个性化的帮助服务和指示,或对您和其他用户作出其他方面的回应。 (5) 用于 对本网站 /软件用户数据的统计（如用户数量、构成、兴趣、表现等），以便我们向用户提供更好的服务和产品。(6)让您参与有关我们产品和服务的调查。为了让您有更好的体验、改善我们的服务或您同意的其他用途,在符合相关法律法规的前提下,我们可能将通过某一项服务所收集的信息,以汇集信息或者个性化的方式,用于我们的其他服务。例如,在您使用我们的一项服务时所收集的信息,可能在另一服务中用于向您提供特定内容,或向您展示与您相关的、非普遍推送的信息。如果我们在相关服务中提供了相应选项,您也可以授权我们将该服务所提供和储存的信息用 于我们的其他服务。

 

3. 我们可能分享、披露的信息

软件信息所收集您的个人信息，将仅用于软件为您提供相关服务。一般情况下，在未得到您的同意之前，您的任何个人信息将不会被提供给无关的第三方。 在下列情况下，我们可能会将您的个人信息提供给有关的第三方： (1)本网站/软件的某些服务需由一个合作伙伴(如代理公司、提供相关技术服务的公司、运输公司、邮寄公司等)提供或共同提供。为了向您提供此类服务，本网站有必要与该合作伙伴分享您的个人信息。如果您不希望您的信息被分享，您可以不使用该具体服务从而拒绝您的个人信息被披露。 (2)根据法律法规或政府的强制性规定，我们必须向有 关司法或政府部门提供您的个人信息。  (3)为了防止他人的合法权益或社会公共利益受到重大危害。 (4)为了防止您的合法权益受到重大危害。 (5)我们发现您的行为违反了本网站/软件的服务条款或关于特定服务或产品的任何使用指引， 或对软件信息的合法权益构成重大危害。

 

 

（一）第三方及其他合法目的分享您的信息：

（ 1 ） 、我们可能向合作伙伴或第三方服务商分享您的信息，以实现您需要的功能或服务，例如：向视频服务商提供对应的信息；

（ 2 ） 、我们可能向合作伙伴及其他第三方分享您的信息，以帮助我们为您提供更有针对性、更完善的服务，例如：代表我们发出推送通知的通讯服务提供商；

（ 3 ） 、我们向第三方合作方提供的信息，不会提供可以识别您身份的信息，例如姓名电话号码或银行账号；

（ 4 ） 、在法律法规允许的范围内，为了遵守法律、维护我们及我们的关联方或合作伙伴、您或其他主角用户或社会公众利益、财产或安全免遭损害，比如为防止欺诈等违法活动和减少信用风险，我们可能与其他公司和组织交换信息。不过 ,这并不包括违反本《隐私政策》中所作的承诺而为获利目的出售、出租、共享或以其它方式披露的信息。

（ 5 ） 、我们会对符合社会公共利益之目的的公司、组织和个人，包括您的合法监护人，要求他们按照我们的说明、按符合本《隐私政策》相关措施来处理信息。

（ 6 ） 、我们的服务包括使用第三方提供的平台。例如：您使用视频播放、即时通讯， 定向推送 这些功能可能会收集您的相关信息。

（ 7 ） 、我们通过其他方式向您提供的平台或链接，使您可以接入第三方的服务或网站，您使用这些平台的服务，须受该第三方的服务条款及隐私政策约束，您需要仔细阅读其条款。本《隐私政策》仅适用于我们所收集的信息，并不适用于任何第三方提供的服务或第三方的信息使用规则，我们对任何第三方使用由您提供的信息不承担责任。

 

（二）信息披露：

（ 1 ） 、在您的明确同意下，根据您的需求和方式披露您的信息；

（ 2 ） 、在符合法律法规的前提下，根据行政执法或司法要求所必须提供您信息的情况，我们会要求对方必须出具与之相应的法律文件，如传票或调查函。我们会对请求进行缜密的审查，以确保其合法合规。

 

4. 用户管理

您可以在任何时候通过使用您的本网站 /软件的用户名和密码，查询，补充或更正您的本网站/软件帐户中的个人信息，或要求删除您的本网站/软件帐户中的个人信息。您在本网站/软件的帐户可以被删除，但这样会导致您不能够登录本网站/软件及使用本网站/软件的任何服务。若发现任何非法使用用户帐号或存在安全漏洞的情况,请立即通知本站并向公安机关报案。因用户未妥善保管其帐号名称及密码而导致第三人使用帐号而给用户造成的损失,由用户自行承担

用户在及使用时应承诺遵守法律法规、社会主义制度、国家利益、公民合法权益、公共秩序、社会道德风尚和信息真实性等七条底线 ,且用户对其发布的内容(包括但不限于文字、图片等)时,不得有以下情形：

(1)违反宪法或法律法规规定的;

(2)危害国家安全,泄露国家秘密,颠覆国家政权,破坏国家统一的;

(3)损害国家荣誉和利益的,损害公共利益的;

(4)煽动民族仇恨、民族歧视,破坏民族团结的;

(5)破坏国家宗教政策,宣扬邪教和封建迷信的;

(6)散布谣言,扰乱社会秩序,破坏社会稳定的;

(7)散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的

(8)侮辱或者诽谤他人,侵害他人合法权益的;

(9)含有法律、行政法规禁止的其他内容或违反软件的其他规定的。

用户以虚假信息骗取帐号名称 ,或其帐号头像、简介等信息存在违法和不良信息的,软件有权采取通知限期改正、暂停使用、注销登记等措施。

5. 群组管理

创建群组人数上限为 500人，群组成员不得复制、发布、传播违法信息；群组创建者和群组管理员应对群组负有管理责任，对于违反上述告知行为的群组，我们有权利对该群组采取限制新成员加入、中止或终止该群组聊天服务等措施。

6. 信息安全

您的本网站 /软件帐户信息和本网站/软件档案通过密码来保护，只有您个人才有权获取这些个人信息。我们建议您不要向任何他人泄露您的密码。本网站/软件也不会主动致电或主动发电子邮件给您来询问您的密码，如果您遇到此类情况请及时与软件客服联系确认。同时，请您在完成工作后，务必退出您的本网站/软件帐户并关闭您的浏览器窗口，以便当您与他人合用一台电脑，或正在公共场所使用电脑时，保证他人无法获取您的个人信息和往来通信。遗憾的是，任何通过互联网或无线网络的数据传输都无法获得绝对的安全保证。所以，在我们尽最大努力来保护您的个人信 息的同时，本网站 /软件不能对您向我们进行的信息传输，或我们所发的在线产品或服务的信息的安全作出肯定或保证。您作出上述行为时将自己承担风险。一旦我们收到您传输来的信息，我们将尽最大努力保证其在我们系统的安全。

7. 用户隐私制度

我们会在您自愿的前提下收集您的个人信息并将这些信息进行整合 ,包括但不限于 帐号时的相关信息和活动报名信息等。尊重用户个人隐私是软件的一项基本政策。所以 ,软件不会公开或向任何第三方透露用户的资料以及用户因享受软件服务而提供的任何信息,但以下情形除外:

(1)事先获得用户的明确授权或许可;

(2)遵守有关法律规定,包括在国家有关机关查询时,提供用户的信息、用户在本站发布的信息内容及其发布时间、互联网地址或者域名等

(3)在紧急情况下竭力维护用户个人和社会大众的隐私安全;

(4)根据本条款相关规定或者软件认为必要的其他情形。软件可能会与第三方合作向用户提供相关的网络服务,在此情况下,如该第三方同意承担与软件同等的保护用户隐私的责任,则软件可将用户信息提供给该第三方。

8. 所有权及知识产权条款

本“网站 /软件”由软件拥有和运作。本“网站/软件”公开或显示的所有内容，包括但不限于文字、图表、照片、图像、动画、音效、插图及软件（简称“内容”）等，均属软件及其许可人或内容供应商所有。本“网站/软件”内的所有组成要素，包括但不限于整体设计及“内容”，均受商业外观、版权、道德规范、商标及其它所有相关知识产权的法律保护。除非依据本条款与条件或与经软件在其它协议的声明同意外，本网站/软件內的任何部分或组成要素或“內容”均不得以任何方式复制或传播。除非有明确协议同意，否则网站/软件中的“內容”及一切相关权利将为软 件成员或其许可人的资产。

9. 责任限制

除非另有明确的书面说明 ,本站及其所包含的或以其它方式通过本站提供给您的全部信息、内容、材料、产品(包括软件)和服务,均是在"按现状"和"按现有"的基础上提供的。

除非另有明确的书面说明 ,我们不对本站的运营及其包含在本站上的信息、内容、材料、产品(包括软件)或服务作任何形式的、明示或默示的声明或担保(根据中华人民共和国法律 另有规定的以外 )。如因不可抗力或其它本站无法控制的原因使本站服务系统溃或无法正常使用导致服务不可用或网上交易无法完成或丢失有关的信息、记录等,我们会合理地尽力协助处理善后事宜

10. 免责声明

客户明确同意使用软件服务的风险由客户承担。软件明确表示不提供任何类型的担保，不论是明确的或隐含的。软件将尽力维护客户使用软件服务的合法权益，但不担保软件服务一定能满足客户的要求。对客户使用该等服务中出现的信息（包括但不限于客户发布的信息）删除或储存失败，软件亦不承担任何责任。

客户理解并接受下载或通过相应产品服务取得的任何信息资料取决于客户自己，并自行承担系统受损、资料丢失以及其他任何风险。

软件对直接、间接、偶然、特殊及继起的损害不负责任，这些损害来自：不正当使用软件服务及 /或应用服务，在非法使用服务或客户传送的信息有所变动。

软件对本项服务下涉及的境内外基础运营商的通信网络的故障、技术缺陷、覆盖范围限制、不可抗力、黑客攻击、客户所在位置、客户关机或其他非软件技术能力范围内的事因等造成的服务中断、客户邮件内容丢失、出现乱码、错误接收、无法接收、迟延接收不承担责任。

软件保留判定客户的行为是否符合本服务协议要求的权利，如果客户违背了本服务协议的规定，软件有权中止或终止相应软件服务。   

转户规定：如果客户将手机号码转让给他人，请用户提前自行删除软件内资料。否则，受让该号码的客户将能够查看您的客户个人数据。软件不承担由此引发的客户数据丢失等责任及纠纷。

11. 协议更新及用户关注义务

根据国家法律法规的更新及网站运营需要 ,我们有权对本条款不时地进行修改,修改后的服务条款一旦被张贴在本站上即生效力,并代替原来的服务条款。用户可随时登录查阅最新服务

条款内容。如用户不同意更新后的服务条款 ,应立即停止接受本站提供的服务;如用户继续使用本站提供的服务,即视为同意更新后的用户协议。我们建议您在使用本站之前阅读本用户

协议及本站的公告。如果本用户协议中任何一条被视为废止无效或因任何理由不可执行 ,该条应视为可分的且并不影响任何其余协议的有效性和可执行性。

12. 法律管辖和适用

本用户协议的订立、执行和解释及争议的解决均应适用在中华人民共和国大陆地区现行之有效法律。如发生本服务条款的某些内容与适用之法律相抵触时 ,则这些条款将完全按法律规定重新解释,而其它有效条款继续有效。如缔约方就本条款內容或其执行发生任何争议,双方应尽力友好协商解决;协商不成时,任何一方均可向咕咚所在地的中华人民共和国大陆地区法院提起诉讼。

13. 其他

我们尊重用户和消费者的合法权利 ,本用户协议及本站上发布的各类规则、政策、声明等其他内容,均是为了更好的、更加便利的为用户和消费者提供服务。软件欢迎用户和社会各界提出意见和建议,我们将虚心接受并适时修改本服务条款及相关政策、规则。

您完成且成为软件的用户则意味着您完全接受本用户协议 ,在之前请您再次确认已知悉并完全理解本用户协议的全部内容。`,
    condition:`隐私政策
一、引言
欢迎您使用我们的产品和服务！我们非常重视您的隐私保护和个人信息保护。本《隐私政策》将帮助您了解以下内容：

为切实保护用户隐私权，优化用户体验，本公司（广东马上信息科技有限公司，以下简称我们）根据现行法规及政策，制定本《隐私政策》。本《隐私政策》将详细说明我们在获取、管理及保护用户个人信息方面的政策及措施。本《隐私政策》适用于我们向您提供的所有服务，无论您是通过计算机设备、移动终端或其他设备获得的我们服务。

本《隐私政策》旨在帮助您了解我们会收集哪些数据、为什么收集这些数据，会利用这些数据做些什么及如何保护这些数据。我们希望您在使用我们服务前仔细阅读并明确您已经充分理解、接受本《隐私政策》的内容，希望您可以根据自己的理解做出合适的选择。您一旦开始使用我们平台的服务，即表示您认同我们在本《隐私政策》中所述内容。在我们更新本《隐私政策》后，您继续使用我们的产品与/或服务，即意味着您同意本《隐私政策》(含更新版本)内容，并且同意我们按照本《隐私政策》收集、使用、保存和共享您的相关信息。此外，在将您的信息用于本《隐私政策》未涵盖的用途时，我们会事先征求您的同意。

二、我们如何收集和使用您的个人信息
我们深知个人信息对您的重要性，也深知为您的信息提供有效保护是我们业务健康可持续发展的基石。感谢您对我们平台的使用和信任！我们致力于维持您对我们的信任，恪守适用法律和我们对您的承诺，尽全力保证您的个人信息安全和合理使用。我们郑重承诺，我们将按业界成熟的安全标准，采取相应的安全保护措施来保护您的个人信息。

我们仅会出于本隐私政策所述的以下目的，收集和使用您的个人信息。如果您不提供相关信息，将无法享受我们提供的产品与/或服务。

1.用户信息采集
当您在本网站/软件进行用户登记，使用本网站/软件的服务，以及参加本网站/软件的活动时，即为您同意本网站/软件将收集您的个人信息，并保存记录。本网站/软件收集的个人信息包括但不限于：姓名、性别、生日、城市、手机电话。您主动提供的信息越多及越准确，我们就能够更好地为您提供有关服务。

2.用户信息的使用
本网站/软件收集个人信息的基本目的是为了更好的了解我们的用户，以便向您提供特色服务和个性化服务。个人信息将主要用于以下几个目的：（1）完成您对特定服务或产品的要求。（2）在我们提供服务时,用于身份验证、客户服务、安全防范、诈骗监测、存档和备份用途,确保我们向您提供的产品和服务的安全性。（3）帮助我们设计新服务,改善我们现有服务。(4)使我们更加了解您如何接入和使用我们的服务,从而针对性地回应您的个性化需求,例如语言设定、位置设定、个性化的帮助服务和指示,或对您和其他用户作出其他方面的回应。(5)用于对本网站/软件用户数据的统计（如用户数量、构成、兴趣、表现等），以便我们向用户提供更好的服务和产品。(6)让您参与有关我们产品和服务的调查。为了让您有更好的体验、改善我们的服务或您同意的其他用途,在符合相关法律法规的前提下,我们可能将通过某一项服务所收集的信息,以汇集信息或者个性化的方式,用于我们的其他服务。例如,在您使用我们的一项服务时所收集的信息,可能在另一服务中用于向您提供特定内容,或向您展示与您相关的、非普遍推送的信息。如果我们在相关服务中提供了相应选项,您也可以授权我们将该服务所提供和储存的信息用于我们的其他服务。

三、我们如何共享、公开披露您的个人信息
我们不会向第三方共享、转让您的个人信息，除非经过您本人事先授权同意，或者共享、转让的个人信息是去标识化处理后的信息，且共享第三方无法重新识别此类信息的自然人主体。

1.共享
软件信息所收集您的个人信息，将仅用于软件为您提供相关服务。一般情况下，在未得到您的同意之前，您的任何个人信息将不会被提供给无关的第三方。在下列情况下，我们可能会将您的个人信息提供给有关的第三方：(1)本网站/软件的某些服务需由一个合作伙伴(如代理公司、提供相关技术服务的公司、运输公司、邮寄公司等)提供或共同提供。为了向您提供此类服务，本网站有必要与该合作伙伴分享您的个人信息。如果您不希望您的信息被分享，您可以不使用该具体服务从而拒绝您的个人信息被披露。(2)根据法律法规或政府的强制性规定，我们必须向有关司法或政府部门提供您的个人信息。(3)为了防止他人的合法权益或社会公共利益受到重大危害。(4)为了防止您的合法权益受到重大危害。(5)我们发现您的行为违反了本网站/软件的服务条款或关于特定服务或产品的任何使用指引，或对软件信息的合法权益构成重大危害。

2.公开披露
我们仅会在以下情形下，公开披露您的个人信息：
2.1获得您的明确同意；
2.2基于法律法规、法律程序、诉讼或政府主管部门强制性要求下。
3.共享、公开披露个人信息时事先征得授权同意的例外
在以下情形中，共享、转让、公开披露您的个人信息无需事先征得您的授权同意：
3.1.与国家安全、国防安全直接相关的；
3.2.与公共安全、公共卫生、重大公共利益直接相关的；
3.3.与犯罪侦查、起诉、审判和判决执行等直接相关的；
3.4.出于维护您或其他个人的生命、财产等重大合法权益但又很难得到本人同意的；
3.5.您自行向社会公众公开的个人信息；
3.6.从合法公开披露的信息中收集个人信息的，如合法的新闻报道、政府信息公开等渠道；
3.7.根据个人信息主体要求签订和履行合同所必需的；
3.8.用于维护所提供的产品或服务的安全稳定运行所必需的，包括发现、处置产品或服务的故障；
3.9.法律法规规定的其他情形。
根据法律规定，共享、转让经去标识化处理的个人信息，且确保数据接收方无法复原并重新识别个人信息主体的，不属于个人信息的对外共享、转让及公开披露行为，对此类数据的保存及处理将无需另行向您通知并征得您的同意。
四、我们如何保存及保护您的个人信息
1.保存期限
您在使用我们产品及服务期间，我们将持续为您保存您的个人信息。如果您注销账户或主动删除上述信息，我们将依据网络安全法等法律法规规定保存您的信息。在您注销账户或主动删除上述信息后，我们不会再对您的个人信息进行商业化使用，但我们可能会对您的个人信息进行匿名化处理后使用。

2.保存地域
您的个人信息均储存于中华人民共和国境内。如部分产品或服务涉及跨境，我们需要向境外传输您的个人信息，我们会严格按照法律法规的规定执行，并保证您的个人信息安全。 3.保护措施

3.1我们已采取符合业界标准、合理可行的安全防护措施保护您的信息，防止个人信息遭到未经授权访问、公开披露、使用、修改、损坏或丢失。我们会使用加密技术提高个人信息的安全性；我们会使用受信赖的保护机制防止个人信息遭到恶意攻击；我们会部署访问控制机制，尽力确保只有授权人员才可访问个人信息；以及我们会举办安全培训课程，加强员工对于保护个人信息重要性的认识。

3.2我们从组织建设、制度设计、人员管理、产品技术等方面多维度提升整个系统的安全性。目前，我们的重要信息系统已经通过网络安全等级保护的测评。

3.3我们会采取合理可行的措施，尽力避免收集无关的个人信息。我们只会在达成本政策所述目的所需的期限内保留您的个人信息（除非法律有强制的存留要求）。

3.4互联网并非绝对安全的环境，使用我们平台服务时，我们强烈建议您不要使用非我们平台推荐的通信方式发送您的信息。您可以通过我们的服务建立联系和相互分享。当您通过我们的服务创建交流、分享时，您可以自主选择沟通、分享的对象，作为能够看到您的联络方式、交流信息或分享内容等相关信息的第三方。

3.5在使用平台服务进行沟通时，请您妥善保护自己的个人信息，仅在必要的情形下向他人提供。如您发现自己的个人信息尤其是您的账户或密码发生泄露，请您立即联络我们，以便我们根据您的申请采取相应措施。

3.6请注意，您在使用我们服务时自愿共享甚至公开分享的信息，可能会涉及您或他人的个人信息甚至个人敏感信息，如您在评论、沟通时选择上传包含个人信息的图片。请您更加谨慎地考虑，是否在使用我们的服务时共享甚至公开分享相关信息。请使用复杂密码，协助我们保证您的账号安全。我们将尽力保障您发送给我们的任何信息的安全性。

3.7根据我们的安全管理制度，个人信息泄露、毁损或丢失事件被列为最特大安全事件，一经发生将启动公司最高级别的紧急预案，由安全部、政府关系部、法务部等多个部门组成联合应急响应小组处理。

4.安全事件通知
4.1.我们会制定网络安全事件应急预案，及时处置系统漏洞、计算机病毒、网络攻击、网络侵入等安全风险，在发生危害网络安全的事件时，我们会立即启动应急预案，采取相应的补救措施，并按照规定向有关主管部门报告。

4.2.个人信息泄露、毁损、丢失属于公司级特大安全事件，我们会负责定期组织工作组成员进行安全预案演练，防止此类安全事件发生。若一旦不幸发生，我们将按照最高优先级启动应急预案，由安全部、政府关系部、法务部等多个部门组成应急响应小组，在最短时间内追溯原因并减少损失。

4.3.在不幸发生个人信息安全事件后，我们将按照法律法规的要求，及时通过软件内部提醒、邮件、短信或电话等方式向您告知安全事件的基本情况和可能的影响、我们已采取或将要采取的处理措施、您可自主防范和降低的风险的建议、对您的补救措施等。我们将及时将事件相关情况通过上述方式告知您，难以逐一告知时我们会采取合理、有效的方式发布公告。同时，我们还将按照监管部门要求，主动上报个人信息安全事件的处置情况。

我们将不定期更新并公开安全风险、个人信息安全影响评估报告等有关内容，您可通过平台公告方式获得。`

  }),

  mounted(){
    var ele_11 = $('.max-length-11-staff-input')
    var ele_11_mo = $('.max-length-11-staff-input-mo')
    ele_11.find('input').attr("maxlength","11")
    ele_11_mo.find('input').attr("maxlength","11")
  },

  computed: {
    ...mapGetters({
      authenticated: 'auth/check'
    })
  },

  methods: {
    async login(){
      if(this.phoneNumber.trim() == ''){
        return this.$snackbar.showMessage({content: '电话号码字段为空', color: 'error'});
      }
      if(/^\d*$/.test(this.phoneNumber) == false){
        return this.$snackbar.showMessage({content: '请输入正确的电话号码', color: 'error'});
      }
      if(this.password.trim() == ''){
        return this.$snackbar.showMessage({content: '密码输入字段为空', color: 'error'});
      }
      if(this.agreeTerms == false){
        return this.$snackbar.showMessage({content: '您必须同意《用户服务协议》和《隐私》才能使用此服务。', color: 'error'})
      }
      this.isLogging = true;
      let payload = {
        phoneNumber : this.phoneNumber,
        password : this.password
      };
      await loginApi(payload)
        .then(res=>{
          this.isLogging = false;
          // Save the token.
          this.$store.dispatch('auth/saveToken', {
            token: res.data.token,
            remember: this.remember
          })
          // Fetch the user.
          
          this.$store.dispatch('auth/saveUserState', res.data.user)
          this.$store.dispatch('schooltree/storeSchoolData', res.data.schoolTree);
          res.data.alarmData.map(alarm => {
            alarm.content = JSON.parse(alarm.content);
          })
          this.$store.dispatch('alarm/storeAlarm', res.data.alarmData);

          //save MemberData
          if( res.data.memberData !== null && (res.data.user.roleId == 3 || res.data.user.roleId == 4 || res.data.user.roleId == 5) ) {
            this.$store.dispatch('schooltree/storeMemberData', res.data.memberData);
          }
          
          // Redirect home.
          this.$router.push({ name: 'home' })
        })
        .catch(err=>{
          //console.log(err);
          this.isLogging = false;
          this.$snackbar.showMessage({content: "登录失败", color: "error"})
        })
    }
  },
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>
