<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ThreeDodIcon from '@/Components/Chat/ThreeDodIcon.vue';
import ChatHeader from '@/Pages/Chat/Partial/ChatHeader.vue';
import { ref } from 'vue';
import axios from "axios";

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

  // Make an Axios request to get messages for the selected user

  try {
    const response = await axios.get(`/chat/messages/${user.id}`);
    messages.value = response.data.messages;
    // console.log(response.data);
    console.log(messages.value);
  } catch (error) {
    console.error('the error is:', error);
  }

};
// const openChat = (user) => {
//   selectedUser.value = user; // Set the selected user
//   isChatOpen.value = true;   // Open the chat window

//   // Make an Axios request to get messages for the selected user
//   axios.get(`/chat/messages/${user.id}`) // Pass the receiver id in parameter
//     .then(response => {
//       console.log(response.data);
//     })
//     .catch(error => {
//       console.error('the error is: ', error); // Log any errors that occur
//     });
// };

const chatClose = () => {
  isChatOpen.value = false;
}

window.sendMessage = function () {
  const message = document.getElementById('messageInput').value;
  const receiverId = selectedUser.value.id;

  messages.value.push({ sender: props.authUser, message: message });
  axios.post('/new/chat', {
    message: message,
    receiver_id: receiverId
  }).then(response => {
    console.log(response.data);
    messageInput.value = '';
  })
  // console.log(message, receiverId);

}

window.Echo.private('chat.' + props.authUser.id)
  .listen('ChatEvent', (e) => {
    console.log('Message received:', e.chatMessage);
    if (isChatOpen.value === true) {
      messages.value.push({ sender: props.authUser.name, message: e.chatMessage.message });   //ekhanea snder deoyar karon holo condition check korar jonnea.
      console.log(e.chatMessage.message);
    } else {
      alert(e.chatMessage.message)
    }
    
  });

</script>

<template>
  <AppLayout title="ChatApp">
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
                    <div class="w-full bg-green-400 h-16 pt-2 text-white flex justify-between shadow-md" style="top:0px; overscroll-behavior: none;">
                      <!-- back button -->
                      <a href="/chat">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-12 h-12 my-1 text-green-100 ml-2">
                          <path class="text-green-100 fill-current" d="M9.41 11H17a1 1 0 0 1 0 2H9.41l2.3 2.3a1 1 0 1 1-1.42 1.4l-4-4a1 1 0 0 1 0-1.4l4-4a1 1 0 0 1 1.42 1.4L9.4 11z" />
                        </svg>
                      </a>
                      <div class="my-3 text-green-100 font-bold text-lg tracking-wide">@{{ selectedUser.name }}</div>
                      <!-- 3 dots -->
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon-dots-vertical w-8 h-8 mt-2 mr-2">
                        <path class="text-green-100 fill-current" fill-rule="evenodd" d="M12 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
                      </svg>
                    </div>


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
                    <!-- message start -->
                    <!-- <div class="mt-20 mb-16">
                      <div v-for="message in messages" :key="message.id">
                        <div v-if="message.sender.name != authUser.name" class="clearfix w-4/4">
                          <div class="bg-gray-300 mx-4 my-2 p-2 rounded-lg inline-block">{{ message.message }}</div>
                        </div>

                        <div v-else class="clearfix w-4/4">
                          <div class="bg-green-300 float-right mx-4 my-2 p-2 rounded-lg "> {{ message.message }}</div>
                        </div>
                      </div>
                    </div> -->
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
