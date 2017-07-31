<template>
<div id="carousel">
  <el-carousel :interval="3000" type="card" height="300px">
    <el-carousel-item v-for="url in urls" :key="url">
      <img :src='url' style="height: inherit;width: 400px">
    </el-carousel-item>
  </el-carousel>
</div>
</template>

<script>
export default {
  name: 'carousel',
  data () {
    return {
      urls: [],
    }
  },
}
</script>

<style>
  .el-carousel__item {
    color: #475669;
    font-size: 14px;
    opacity: 1;
    line-height: 100px;
    text-align: center;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: transparent;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: transparent;
  }
</style>