<template>
  <div class="editor">
    <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
    <editor-floating-menu
      :editor="editor"
      v-slot="{ commands, isActive, menu }"
    >
      <div
        class="editor__floating-menu"
        :class="{ 'is-active': menu.isActive }"
        :style="`top: ${menu.top}px`"
      >
        <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
        <v-tooltip top v-for="button in floatingMenuButtons" :key="button.icon">
          <template v-slot:activator="{ on }">
            <v-btn
              class="menubar__button"
              @click="commands[button.command](button.context)"
              :class="{
                'is-active': isActive[button.active](button.context),
              }"
              small
              v-on="on"
            >
              <v-icon>mdi-{{ button.icon }}</v-icon>
            </v-btn>
          </template>
          <span>{{ button.tooltip }}</span>
        </v-tooltip>
        <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <v-btn
              class="menubar__button"
              @click.stop="openModal(commands.image)"
              :class="{
                'is-active': isActive.image,
              }"
              small
              v-on="on"
            >
              <v-icon>mdi-image</v-icon>
            </v-btn>
          </template>
          <span>image upload</span>
        </v-tooltip>
        <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
      </div>
    </editor-floating-menu>
    <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
    <editor-menu-bubble
      :editor="editor"
      v-slot="{ commands, isActive, menu }"
      :keep-in-bounds="true"
    >
      <div
        class="menububble"
        :class="{ 'is-active': menu.isActive }"
        :style="`left: ${menu.left}px;bottom: ${menu.bottom}px`"
      >
        <v-btn
          v-for="button in bubbleMenuButtons"
          :key="button.icon"
          class="menububble__button"
          @click="commands[button.command](button.context)"
          :class="{
            'is-active': isActive[button.active](button.context),
          }"
          small
          dark
        >
          <v-icon>mdi-{{ button.icon }}</v-icon>
        </v-btn>
      </div>
    </editor-menu-bubble>
    <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->

    <editor-content :editor="editor"></editor-content>

    <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
    <dropzone-modal ref="dropzone" @image="uploadImage"></dropzone-modal>
    <!-- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ---- ----   -->
  </div>
</template>

<script>
import {
  Editor,
  EditorContent,
  EditorFloatingMenu,
  EditorMenuBubble,
} from "tiptap";
import {
  Image,
  Placeholder,
  Heading,
  Bold,
  Italic,
  History,
  Blockquote,
  BulletList,
  CodeBlock,
  HardBreak,
  ListItem,
  OrderedList,
  TodoItem,
  TodoList,
  Code,
  Link,
} from "tiptap-extensions";
import DropzoneModal from "./DropzoneModal";
import { ref, onUnmounted } from "@vue/composition-api";

export default {
  components: {
    EditorContent,
    EditorFloatingMenu,
    EditorMenuBubble,
    DropzoneModal,
  },
  props: {
    value: {
      type: String,
      default: "",
    },
  },
  // Setup Composition Api
  setup(props, { emit }) {
    const bubbleMenuButtons = ref([
      {
        active: "italic",
        command: "italic",
        icon: "format-italic",
        tooltip: "ایتالیک",
        context: {},
      },
      {
        active: "bold",
        command: "bold",
        icon: "format-bold",
        tooltip: "درشت ",
        context: {},
      },
      {
        active: "heading",
        command: "heading",
        icon: "format-header-1",
        tooltip: "هدر 1 ",
        context: { level: 1 },
      },
      {
        active: "heading",
        command: "heading",
        icon: "format-header-2",
        tooltip: "هدر 2 ",
        context: { level: 2 },
      },
      {
        active: "heading",
        command: "heading",
        icon: "format-header-3",
        tooltip: "هدر 3 ",
        context: { level: 3 },
      },
    ]);

    const floatingMenuButtons = ref([
      {
        active: "bullet_list",
        command: "bullet_list",
        icon: "format-list-bulleted",
        tooltip: "bullet_list",
        context: {},
      },
      {
        active: "ordered_list",
        command: "ordered_list",
        icon: "format-list-numbered",
        tooltip: "ordered_list",
        context: {},
      },
      {
        active: "blockquote",
        command: "blockquote",
        icon: "format-quote-open",
        tooltip: "blockquote",
        context: {},
      },
      {
        active: "code_block",
        command: "code_block",
        icon: "code-braces",
        tooltip: "code_block",
        context: {},
      },
      {
        active: "italic",
        command: "italic",
        icon: "format-italic",
        tooltip: "ایتالیک",
        context: {},
      },
      {
        active: "bold",
        command: "bold",
        icon: "format-bold",
        tooltip: "درشت ",
        context: {},
      },
      {
        active: "heading",
        command: "heading",
        icon: "format-header-1",
        tooltip: "هدر 1 ",
        context: { level: 1 },
      },
      {
        active: "heading",
        command: "heading",
        icon: "format-header-2",
        tooltip: "هدر 2 ",
        context: { level: 2 },
      },
      {
        active: "heading",
        command: "heading",
        icon: "format-header-3",
        tooltip: "هدر 3 ",
        context: { level: 3 },
      },
    ]);

    const editor = ref(
      new Editor({
        extensions: [
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Code(),
          new Link(),
          new Italic(),
          new Heading({ level: [1, 2, 3] }),
          new History(),
          new Bold(),
          new Image(),
          new Placeholder({
            emptyEditorClass: "is-editor-empty",
            emptyNodeClass: "is-empty",
            emptyNodeText: "هر چیزی که میخوای بنویس...",
            showOnlyWhenEditable: true,
            showOnlyCurrent: true,
          }),
        ],
        content: props.value,
        onUpdate: ({ getHTML }) => {
          emit("input", getHTML());
        },
      })
    );

    const dropzone = ref(null);

    const openModal = (imageCommand) => {
      dropzone.value.showModal(imageCommand);
    };
    const uploadImage = (data) => {
      data.imageCommand(data.attrs);
    };

    onUnmounted(() => {
      editor.value.destroy();
    });

    return {
      bubbleMenuButtons,
      floatingMenuButtons,
      editor,
      dropzone,
      openModal,
      uploadImage,
    };
  },
};
</script>


<style lang="scss">
.editor {
  position: relative;
  &__floating-menu {
    position: absolute;
    z-index: 1;
    margin-top: -0.25rem;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.2s, visibility 0.2s;
    &.is-active {
      opacity: 1;
      visibility: visible;
    }
  }
}
.editor p.is-editor-empty:first-child::before {
  content: attr(data-empty-text);
  float: right;
  color: #aaa;
  pointer-events: none;
  height: 0;
  font-style: italic;
}
</style>
