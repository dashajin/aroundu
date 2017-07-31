<template>
    <div id="noticebar">
        <el-menu :default-active="activeitem" class="el-menu-vertical-demo" @select="handleSelect">
            <el-menu-item index="1">
                <i class="fa fa-comments"></i> 评论
                <span class="badge" style="background-color: red"></span>
            </el-menu-item>
            <el-menu-item index="2">
                <i class="fa fa-heart"></i> 喜欢和赞
            </el-menu-item>
            <el-menu-item index="3">
                <i class="fa fa-male"></i> 关注
                <span class="badge" style="background-color: red"></span>
            </el-menu-item>
        </el-menu>
    </div>
</template>

<script>
    export default {
        name: 'noticebar',
        data() {
            return {

            }
        },
        props: {
            activeitem: '0',
        },
        methods: {
            handleSelect(key, keyPath) {
                if (key == 1) {
                    window.location.href = '/notice/comment';
                }
            },
        }
    }
</script>