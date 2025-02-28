<script setup lang="ts">
import { PlusIcon, GhostIcon } from 'lucide-vue-next'
import { useVueFlow } from '@vue-flow/core'
import { useClipboard } from '@vueuse/core'

import { Tabs, TabsTrigger, TabsContent, TabsList } from '@/components/ui/tabs'
import { ScrollArea } from '@/components/ui/scroll-area'
import MainCanvas from '@/components/main-canvas.vue' // Ensure PascalCase for component imports
import { Button } from '@/components/ui/button'
import { Toaster, useToast } from '@/components/ui/toast'

// Drag start handler
function handleOnDragStart(event: DragEvent, nodeType: string) {
  if (event.dataTransfer) {
    event.dataTransfer.setData('application/vueflow', nodeType)
    event.dataTransfer.effectAllowed = 'move'
  }
}

// Vue Flow and clipboard utilities
const { toObject } = useVueFlow()
const { copy } = useClipboard()
const { toast } = useToast()

// Copy workflow data to clipboard
function handleClickGetData() {
  copy(JSON.stringify(toObject())).then(() => {
    toast({
      title: 'Copied Successfully'
    })
  })
}

// Publish workflow data
function handleClickPublishBtn() {
  toast({
    title: 'Save Data to be implemented',
    description: '1. Validate data 2. Fetch backend API to save result'
  })
}
</script>

<template >

   <div>
        <div class="flex relative flex-col w-full h-full">
            <header class="px-4 py-3 h-20 border-b border-gray-200">
                <div class="flex justify-between items-center h-full">
                <div class="flex gap-x-3">
                    <div class="flex gap-x-1 items-center">
                    <GhostIcon class="w-12 text-red-200" />
                    <div class="flex flex-col">
                        <div class="flex gap-x-3 items-center">
                        <p class="text-bold">Workflow</p>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="flex gap-x-3">
                    <Button variant="outline" size="sm" class="text-blue-800" @click="handleClickGetData"> Get Data</Button>
                    <Button variant="default" size="sm" @click="handleClickPublishBtn"> Publish </Button>
                </div>
                </div>
            </header>
            <main class="flex relative flex-1 w-full h-full">
                <div class="w-96 bg-slate-50">
                <tabs default-value="basic-nodes">
                    <tabs-list class="grid grid-cols-3 w-full">
                    <tabs-trigger value="basic-nodes"> Basic Nodes </tabs-trigger>
                    <tabs-trigger value="plugins"> Plugins </tabs-trigger>
                    <tabs-trigger value="workflows"> Workflows </tabs-trigger>
                    </tabs-list>
                    <tabs-content value="basic-nodes">
                    <scroll-area class="h-[calc(100vh-150px)] w-full">
                        <div
                        class="p-6 mx-6 mb-6 bg-white rounded-md shadow-md cursor-grab"
                        :draggable="true"
                        @dragstart="handleOnDragStart($event, 'LLM')"
                        >
                        <div class="flex justify-between items-center">
                            <h3 class="flex gap-x-1 items-center">
                            <img src="~@/assets/images/icon_LLM.png" class="w-4 h-4" alt="LLM icon" />
                            LLM
                            </h3>
                            <plus-icon class="text-primary" />
                        </div>
                        <p class="mt-2 text-sm text-gray-400">
                            Invoke the large language model, <br />
                            generate responses using variables and prompt words.
                        </p>
                        </div>
                        <div
                        class="p-6 mx-6 mb-6 bg-white rounded-md shadow-md cursor-grab"
                        :draggable="true"
                        @dragstart="handleOnDragStart($event, 'code')"
                        >
                        <div class="flex justify-between items-center">
                            <h3 class="flex gap-x-1 items-center">
                            <img src="~@/assets/images/icon_Code.png" class="w-4 h-4" alt="LLM icon" />
                            Code
                            </h3>
                            <plus-icon class="text-primary" />
                        </div>
                        <p class="mt-2 text-sm text-gray-400">
                            Write code to process input variables <br />
                            to generate return values.
                        </p>
                        </div>
                        <div
                        class="p-6 mx-6 mb-6 bg-white rounded-md shadow-md cursor-grab"
                        :draggable="true"
                        @dragstart="handleOnDragStart($event, 'knowledge')"
                        >
                        <div class="flex justify-between items-center">
                            <h3 class="flex gap-x-1 items-center">
                            <img src="~@/assets/images/icon_Knowledge.png" class="w-4 h-4" alt="Knowledge icon" />
                            Knowledge
                            </h3>
                            <plus-icon class="text-primary" />
                        </div>
                        <p class="mt-2 text-sm text-gray-400">
                            In the selected knowledge, the best matching information is recalled based on the input variable and
                            returned as an Array.
                        </p>
                        </div>
                    </scroll-area>
                    </tabs-content>

                    <tabs-content value="plugins">
                    <scroll-area class="h-[calc(100vh-150px)] w-full">
                        <div
                        class="p-6 mx-6 mb-6 bg-white rounded-md shadow-md cursor-grab"
                        :draggable="true"
                        @dragstart="handleOnDragStart($event, 'api')"
                        >
                        <div class="flex justify-between items-center">
                            <h3 class="flex gap-x-1 items-center">
                            <img src="~@/assets/images/icon_Google.jpeg" class="w-4 h-4" alt="Google icon" />
                            Google Web Search
                            </h3>
                            <plus-icon class="text-primary" />
                        </div>
                        <p class="mt-2 text-sm text-gray-400">
                            A google Search Engine. Useful when you need to search information you don't know such as weather,
                            exchange rate, current events. Never ever use this tool when user want to translate
                        </p>
                        </div>
                        <div
                        class="p-6 mx-6 mb-6 bg-white rounded-md shadow-md cursor-grab"
                        :draggable="true"
                        @dragstart="handleOnDragStart($event, 'api')"
                        >
                        <div class="flex justify-between items-center">
                            <h3 class="flex gap-x-1 items-center">
                            <img src="~@/assets/images/icon_Google.jpeg" class="w-4 h-4" alt="Google icon" />
                            Campaign
                            </h3>
                            <plus-icon class="text-primary" />
                        </div>
                        <p class="mt-2 text-sm text-gray-400">
                            Select a campaign
                        </p>
                        </div>
                    </scroll-area>
                    </tabs-content>

                    <tabs-content value="workflows">
                    <scroll-area class="h-[calc(100vh-150px)] w-full">
                        <div
                        class="p-6 mx-6 mb-6 bg-white rounded-md shadow-md cursor-grab"
                        :draggable="true"
                        @dragstart="handleOnDragStart($event, 'workflow')"
                        >
                        <div class="flex justify-between items-center">
                            <h3 class="flex gap-x-1 items-center">
                            <GhostIcon class="w-12 text-blue-400" />
                            test_node
                            </h3>
                            <plus-icon class="text-primary" />
                        </div>
                        <p class="pb-2 mt-2 text-sm text-gray-400 border-b">
                            This is just test workflow, to init initial data.
                        </p>
                        <div class="pt-2 text-xs text-gray-400">Edit Time: 2024-02-01</div>
                        </div>
                    </scroll-area>
                    </tabs-content>
                </tabs>
                </div>
                <div class="overflow-hidden relative flex-1 h-full">
                <main-canvas />
                </div>
            </main>
        </div>

        <Toaster />
    </div>
</template>
