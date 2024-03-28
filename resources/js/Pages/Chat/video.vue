<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ThreeDodIcon from '@/Components/Chat/ThreeDodIcon.vue';
import ChatHeader from '@/Pages/Chat/Partial/ChatHeader.vue';
import { ref } from 'vue';
import { Peer } from "https://esm.sh/peerjs@1.5.2?bundle-deps";

const props = defineProps({
  users: Array,
  authUserId: Number
});
function logout2() {
  console.log('logout')
}
function fileLink(file) {
  return '/assets/' + file;
}

const selectedUser = ref(null);
const isChatOpen = ref(false);
const peer = new Peer();

let localStream;
let remoteStream;

const openChat = (user) => {
  selectedUser.value = user;
  isChatOpen.value = true;
  startVideoStream();
};

const chatClose = () => {
  isChatOpen.value = false;
  stopVideoStream();
};

peer.on('open', (id) => {
  const peeridconnza = document.getElementById('peeridconnz');
  peeridconnza.innerText = id;
  console.log('My peer ID is: ' + id);
});

peer.on('call', (call) => {
  navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    .then((stream) => {
      localStream = stream;
      call.answer(stream); // Answer the call with an A/V stream.
      call.on('stream', (remoteStream) => {
        // Show stream in some video/canvas element.
        const video = document.querySelector('.remote-video');
        video.srcObject = remoteStream;
        video.play();
      });
    })
    .catch((err) => {
      console.error('Failed to get local stream', err);
    });
});

window.connectToPeer = function () {
  const peerIdEl = document.getElementById('connectpeerid');
  const receive = peerIdEl.value;
  console.log("Connecting to peer: " + receive);
  const conn = peer.connect(receive);

  conn.on('open', () => {
    console.log('Connected to peer: ' + conn.peer);
    // Request video call
    startVideoStream();
    const call = peer.call(conn.peer, localStream);
    call.on('stream', (remoteStream) => {
      // Show stream in some video/canvas element.
      const video = document.querySelector('.remote-video');
      video.srcObject = remoteStream;
      video.play();
    });
  });
};

function startVideoStream() {
  navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    .then((stream) => {
      localStream = stream;
      const video = document.querySelector('.local-video');
      video.srcObject = stream;
      video.play();
    })
    .catch((err) => {
      console.error('Failed to get local stream', err);
    });
}

function stopVideoStream() {
  if (localStream) {
    localStream.getTracks().forEach((track) => {
      track.stop();
    });
  }
}

window.sendCall = function () {
  // Implement calling functionality here
};
</script>

<template>
  <AppLayout title="ChatApp">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        video Chat connection Id <p id="peeridconnz" style="color: brown;"></p>
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
                      <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1.5 -left-1.5 ml-2">
                      </div>
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
                  <div class="overflow-y-auto max-h-64 min-h-[19rem] px-4">
                    <div class="flex items-center mb-4">
                      <img :src="fileLink('me.jpg')" alt="user" class="w-6 h-6 rounded-full border-2 border-blue-400 mr-1">
                      <div class="relative group text-sm p-2 shadow bg-white rounded-md max-w-xs">
                        message show here
                        <div class="absolute top-0.5 -translate-y-0.5 left-full hidden group-hover:block ml-1 mt-1 bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">
                          12:22
                        </div>
                      </div>
                      <three-dod-icon class="cursor-pointer w-3 h-3"></three-dod-icon>
                    </div>

                    <div class="flex items-center mb-4">
                      <img :src="fileLink('me.jpg')" alt="user" class="w-6 h-6 rounded-full border-2 border-blue-400 mr-1">
                      <div class="relative group text-sm p-2 shadow bg-white rounded-md max-w-xs">
                        my Peer ID is <p id="peerIdShow"></p>

                      </div>
                    </div>

                    <!-- video will show here  -->
                    <div class="flex items-center justify-end mb-4">
                      <video class="local-video" autoplay muted></video>
                    </div>
                    <div class="flex items-center justify-end mb-4">
                      <video class="remote-video" autoplay></video>
                    </div>


                  </div>

                  <!-- chat footer  -->
                  <div class="flex items-center p-5 bg-white rounded-bl-md rounded-br-md">
                    <input id="connectpeerid" type="text" placeholder="connect-to-peer" class="w-full p-2 rounded-md border border-gray-300 focus:outline-non focus:ring focus:border-blue-400">

                    <button onclick="connectToPeer()" class="bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 ml-2">Connect</button>
                    <button onclick="sendCall()" class="bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 ml-2">Call</button>
                  </div>
                </div>

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
