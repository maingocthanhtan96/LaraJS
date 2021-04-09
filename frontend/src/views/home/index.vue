<template>
  <svg viewBox="0 0 1320 300">
    <text x="50%" y="50%" dy=".35em" text-anchor="middle">
      LaraJS
    </text>
  </svg>
</template>

<script>
export default {
  name: 'Home',
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
svg {
  font-family: 'Russo One', sans-serif;
  position: absolute;
  width: 100%; height: 100%;
}
svg text {
  animation: stroke 5s infinite alternate;
  stroke-width: 2;
  stroke: #9F55B6;
  font-size: 140px;
}
@keyframes stroke {
  0%   {
    fill: rgba(72,138,20,0); stroke: rgba(54,95,160,1);
    stroke-dashoffset: 25%; stroke-dasharray: 0 50%; stroke-width: 2;
  }
  70%  {fill: rgba(72,138,20,0); stroke: rgba(54,95,160,1); }
  80%  {fill: rgba(72,138,20,0); stroke: rgba(54,95,160,1); stroke-width: 3; }
  100% {
    fill: rgba(72,138,204,1); stroke: rgba(54,95,160,0);
    stroke-dashoffset: -25%; stroke-dasharray: 50% 0; stroke-width: 0;
  }
}
</style>
