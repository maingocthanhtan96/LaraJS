<template>
  <el-container class="page404 h-screen w-screen d-flex items-center">
    <el-main>
      <el-row type="flex" justify="center" class="text-white">
        <el-col :span="12" class="d-flex text-right items-center">
          <h1 class="text-12r">401</h1>
        </el-col>
        <el-col
          :span="8"
          class="flex flex-col text-left items-center justify-center"
        >
          <div class="error-text">
            <h1 class="text-5xl">SORRY!</h1>
            <p class="text-3xl">You not permission.</p>
          </div>
        </el-col>
        <div class="flex-grow" />
      </el-row>
      <el-row type="flex" justify="center" class="text-center pt-10">
        <el-col :span="24">
          <router-link
            :to="{ name: 'login' }"
            class="cursor-pointer bg-blue hover:underline hover:bg-blue-light text-white font-bold py-2 px-4 border-b-4 border-blue-dark hover:border-blue rounded"
          >
            Back to login
          </router-link>
        </el-col>
      </el-row>
      <el-row type="flex" justify="center" class="text-center text-white pt-24">
        <el-col :span="24">
          Copyright © 2019. All rights reserved.
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.page404 {
  background-color: #25378b;

  .text-12r {
    font-size: 12rem;
  }

  .error-text {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>
