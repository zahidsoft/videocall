<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ThreeDodIcon from '@/Components/Chat/ThreeDodIcon.vue';
import ChatHeader from '@/Pages/Chat/Partial/ChatHeader.vue';
import { ref, onMounted } from 'vue';
import axios, { Axios } from "axios";
// import { Peer } from "https://esm.sh/peerjs@1.5.4?bundle-deps"
import { Peer } from "peerjs";

function logout2() {
  console.log('logout')
}
function fileLink(file) {
  return '/assets/' + file;
}



//recive users data as an arry in props so that vue component access array data
const props = defineProps({
  users: Array,
  authUser: Object,
});

const selectedUser = ref(null);

const isChatOpen = ref(false);
const messages = ref([]);

const openChat = async (user) => {
  selectedUser.value = user; // Set the selected user
  isChatOpen.value = true;   // Open the chat window
};

const chatClose = () => {
  isChatOpen.value = false;
}

const localPeerId = ref(null);
const peer = ref(null);   //new peer er velue ta peer er moddhea rekhea reactive ref er maddhome onno function e bebohar kora hoisea. 

onMounted(() => {
  if (peer.value) {
    peer.value.destroy();
  }
  // new peer () er maddhomea peer id ta create hoy ebon on er maddhomea se niyeai prostut hoy onno peer er sathea connect hoyar jonnea
  peer.value = new Peer(props.authUser.id, {   //peer.value use kora hoisea reactive refrence er jonno jatea ei value ta vue er onno khaneo bebohar kora jay 
    key: 'peerjs',
    secure: true,
    debug: 3
  });

  // peer.on('open',(id)) eta dara bojhassea amie ready asie onno peer er sathea conntact kortea peer id ta prostut asea
  peer.value.on('open', (id) => {
    localPeerId.value = id;
    console.log('My peer ID is: ', localPeerId.value);
  });

  peer.value.on('error', (err) => {
    console.error('PeerJS error: ', err);
  });

  // jokhon user b calback function er maddhomea connect hoyea conn return korbea tokhon se conn er maddhomea data adan prodan hobea
  peer.value.on('connection', function (conn) {
    console.log('connected with user a');
    conn.on('data', function (data) {
      console.log('received from user A: ' + data);
    });
  });
});


//audio calling system 
const SendCall = (selectedUser) => {
  // console.log(selectedUser.id);
  console.log(localPeerId.value);
  axios.post(`/chat/call/notify`, {
    caller_id: props.authUser.id,
    receiver_id: selectedUser.id,
    caller_peer_id: localPeerId.value,
  }).then(response => {
    console.log('message:', response.data)
  }).catch(error => {
    console.log('error: ', error)
  });

  // audio stream sendding 
  navigator.mediaDevices.getUserMedia({ audio: true })
    .then(function (stream) {
      const call = peer.value.call(selectedUser.id, stream);
      // console.log(selectedUser.id);
      call.on('stream', function (remoteStream) {
        const audio = document.createElement('audio');
        audio.srcObject = remoteStream;
        audio.play();
      });
    }).catch(function (err) {
      console.log('fail to get local stream:', err)
    });
}


window.Echo.private('audiocall.' + props.authUser.id)
  .listen('audioCallEvent', (e) => {
    console.log('the peer id is:', e.caller_peer_id, e.receiver_id, e.caller_id);

    const conn = peer.value.connect(e.caller_peer_id);
    conn.on('open', function () {
      console.log('userbe is connected');
      conn.send("hi sir you are connected");
      // console.log(localPeerId.value);
      peer.value.on('call', function (call) {
        navigator.mediaDevices.getUserMedia({ audio: true })
          .then(function (stream) {
            call.answer(stream);
            call.on('stream', function (remoteStream) {
              const audio = document.createElement('audio');
              audio.srcObject = remoteStream;
              audio.play();
            });
          });
      });
    });

  });

</script>

<template>
  <AppLayout title="AudioCall">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        chat Current user Id {{ authUser.id }} {{ authUser.name }}
      </h2>
    </template>

    <div class="py-12 font-sans bg-[#edf2f7]">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow-xl sm:rounded-lg">
          <!-- chat header start -->
          <chat-header></chat-header>
          <!-- chat header end -->
          <div class="lg:container mx-auto mt-2 px-4 mb-4">
            <div class="grid grid-cols-3 gap-4">

              <div class="col-span-1 min-w-[300px] bg-white px-4 pt-2 pb-2 shadow-md rounded-md">
                <input id="search" type="text" placeholder="Search" class="w-full p-2 rounded-md border border-gray-300 focus:outline-non focus:ring focus:border-blue-400 mb-4">
                <!-- chat list -->
                <div class="max-h-96 overflow-y-auto">
                  <div v-for="user in users" :key="user.id" @click="(openChat(user))" class="flex p-2 items-center cursor-pointer rounded-md hover:bg-gray-100">
                    <div class="relative">
                      <img :src="fileLink('me.jpg')" alt="user" class="w-12 h-12 rounded-full border-2 border-blue-400">
                      <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                    </div>
                    <div class="ml-3">
                      <p class="font-semiblod">{{ user.name }}</p>
                      <p class="text-gray-500">{{ user.created_at }}</p>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-span-2 bg-stone-50 mb-4">
                <div v-if="isChatOpen">

                  <!-- chat header  -->
                  <div class="flex items-center justify-between mb-5 bg-slate-200 px-4 pt-2 rounded-tl-md rounded-tr-md">
                    <div class="flex p-2 items-center cursor-pointer rounded-md">
                      <div class="relative">
                        <img :src="fileLink('me.jpg')" alt="user" class="w-12 h-12 rounded-full border-2 border-blue-400">
                        <div class="absolute h-3 w-3 bg-green-600 rounded-full -top-1.5 -left-1.5 ml-2">
                        </div>
                      </div>
                      <div class="ml-3">
                        <p class="font-semiblod">{{ selectedUser.name }} <span>{{ selectedUser.id }}</span></p>
                        <p class="text-gray-500">{{ selectedUser.created_at }}</p>
                      </div>
                      <div class="m-2">
                        <button @click="SendCall(selectedUser)" class="bg-black text-white">call Now</button>
                      </div>
                    </div>
                    <div class="relative inline-block text-left group">
                      <three-dod-icon class="cursor-pointer w-6 h-6"></three-dod-icon>
                      <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                        <div class="py-1">
                          <span @click="chatClose" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Close
                            Chat</span>
                          <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">
                            Clear Chat
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- chat body  -->
                  <div style="overscroll-behavior: none;">
                    <!-- message start -->
                    <div class="mt-20 mb-16">
                      <div v-for="message in messages" :key="message.id">
                        <div v-if="message.sender.name != authUser.name" class="flex justify-start">
                          <div class="bg-gray-300 max-w-xs w-auto p-3 m-1 rounded-lg shadow-md">{{ message.message }}</div>
                        </div>

                        <div v-else class="flex justify-end">
                          <div class="bg-green-300 max-w-xs w-auto p-3 m-1 rounded-lg shadow-md "> {{ message.message }}</div>
                        </div>
                      </div>
                    </div>
                    <!-- message start -->

                  </div>

                  <div class="w-full flex justify-between bg-green-100" style="bottom: 0px;">
                    <textarea id="messageInput" class="flex-grow m-2 py-2 px-4 mr-1 rounded-full border border-gray-300 bg-gray-200 resize-none" rows="1" placeholder="Message..." style="outline: none;"></textarea>
                    <button onclick="sendMessage()" class="m-2" style="outline: none;">
                      <svg class="svg-inline--fa text-green-400 fa-paper-plane fa-w-16 w-12 h-12 py-2 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="paper-plane" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M476 3.2L12.5 270.6c-18.1 10.4-15.8 35.6 2.2 43.2L121 358.4l287.3-253.2c5.5-4.9 13.3 2.6 8.6 8.3L176 407v80.5c0 23.6 28.5 32.9 42.5 15.8L282 426l124.6 52.2c14.2 6 30.4-2.9 33-18.2l72-432C515 7.8 493.3-6.8 476 3.2z" />
                      </svg>
                    </button>
                  </div>
                </div>
                <!-- chat body  End-->

                <div v-else class="flex flex-col items-center justify-center min-h-[19rem]">
                  <img :src="fileLink('me.jpg')" alt="user" class="w-24 h-24 rounded-full border-2 border-blue-400">
                  <p class="text-2xl font-semibold mt-4">top developer zahid</p>
                  <p class="text-gray-500">select a chat to start messaging</p>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

</template>
