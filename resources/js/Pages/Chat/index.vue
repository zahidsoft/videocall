<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ThreeDodIcon from '@/Components/Chat/ThreeDodIcon.vue';

    function logout2() {
        console.log('logout')
    }
    function  fileLink(file) {
        return '/assets/' + file;
    }
    function isChatOpen() {
        return  true;
    }
</script>

<template>
    <AppLayout title="ChatApp">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                chat
            </h2>
        </template>

        <div class="py-12 font-sans bg-[#edf2f7]">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="bg-white border-b border-gray-300 top-0 w-full shadow">
                        <div class="lg:container mx-auto p-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 min-w-[250px]">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img :src="fileLink('me.jpg')" class="w-12 h-12 rounded-full border-2 border-blue-400" alt="hello world">
                                            <span class="font-semibold text-xl pl-1">Zahid Hasan</span>
                                        </div>
                                        <div class="relative inline-block text-left group">
                                            <three-dod-icon class="cursor-pointer w-6 h-6"></three-dod-icon>
                                            <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                                                <div class="py-1">
                                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Profile</a>
                                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Logout</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 text-right">
                                    <button class="bg-red-500 text-white px-4 py-2 rounded-md" @click="logout2">Logout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:container mx-auto mt-2 px-4 mb-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1 min-w-[300px] bg-white px-4 pt-2 pb-2 shadow-md rounded-md">
                                <input id="search" type="text" placeholder="Search" class="w-full p-2 rounded-md border border-gray-300 focus:outline-non focus:ring focus:border-blue-400 mb-4">

                                <!-- chat list -->
                                <div class="max-h-96 overflow-y-auto">
                                    <div class="flex p-2 items-center cursor-pointer rounded-md bg-gray-100">
                                        <div class="relative">
                                            <img :src="fileLink('me.jpg')" alt="user" class="w-12 h-12 rounded-full border-2 border-blue-400">
                                            <div class="absolute h-3 w-3 bg-green-600 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-semiblod">zahid hasan</p>
                                            <p class="text-gray-500">hello</p>
                                        </div>
                                    </div>
                                    <div class="flex p-2 items-center cursor-pointer rounded-md hover:bg-gray-100">
                                        <div class="relative">
                                            <img :src="fileLink('me.jpg')" alt="user" class="w-12 h-12 rounded-full border-2 border-blue-400">
                                            <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-semiblod">zahid hasan</p>
                                            <p class="text-gray-500">hello</p>
                                        </div>
                                    </div>
                                    <div class="flex p-2 items-center cursor-pointer rounded-md hover:bg-gray-100">
                                        <div class="relative">
                                            <img :src="fileLink('me.jpg')" alt="user" class="w-12 h-12 rounded-full border-2 border-blue-400">
                                            <div class="absolute h-3 w-3 bg-green-600 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-semiblod">zahid hasan</p>
                                            <p class="text-gray-500">hello</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-span-2 bg-stone-50 mb-4">
                                <div v-if="isChatOpen()">
                                    <!-- chat header  -->
                                    <div class="flex items-center justify-between mb-5 bg-slate-200 px-4 pt-2 rounded-tl-md rounded-tr-md">
                                        <div class="flex p-2 items-center cursor-pointer rounded-md">
                                            <div class="relative">
                                                <img :src="fileLink('me.jpg')" alt="user" class="w-12 h-12 rounded-full border-2 border-blue-400">
                                                <div class="absolute h-3 w-3 bg-green-600 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-semiblod">zahid hasan</p>
                                                <p class="text-gray-500">hello</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-block text-left group">
                                            <three-dod-icon class="cursor-pointer w-6 h-6"></three-dod-icon>
                                            <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                                                <div class="py-1">
                                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Close Chat</a>
                                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Clear Chat</a>
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
                                                <div class="absolute top-0.5 -translate-y-0.5 left-full hidden group-hover:block ml-1 mt-1 bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">12:22</div>
                                            </div>
                                            <three-dod-icon class="cursor-pointer w-3 h-3"></three-dod-icon>
                                        </div>
                                        <div class="flex items-center justify-end mb-4">
                                            <three-dod-icon class="cursor-pointer w-3 h-3"></three-dod-icon>
                                            <div class="relative group text-sm p-2 shadow bg-indigo-100 rounded-md max-w-xs">
                                                message show here
                                                <div class="absolute top-0.5 -translate-y-0.5 right-full hidden group-hover:block mr-1 mt-1 bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">12:22</div>
                                            </div>
                                            <img :src="fileLink('me.jpg')" alt="user" class="w-6 h-6 rounded-full border-2 border-blue-400 mr-1 ml-1">
                                            
                                        </div>
                                    </div>

                                    <!-- chat footer  -->
                                    <div class="flex items-center p-5 bg-white rounded-bl-md rounded-br-md">
                                        <input id="sendmessage" type="text" placeholder="write message" class="w-full p-2 rounded-md border border-gray-300 focus:outline-non focus:ring focus:border-blue-400">
                                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 ml-2" disabled>send</button>
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
