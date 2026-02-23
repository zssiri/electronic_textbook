<template>
  <div class="definition-card">
    <div class="def-header">
      <span class="def-badge">Термин</span>
    </div>
    <div class="def-body">
      <slot></slot>
    </div>
  </div>
</template>

<style scoped>
.definition-card {
  background: #f8f9ff;
  border: 1px solid #e0e4ff;
  border-radius: 24px;
  padding: 25px;
  margin: 30px 0;
  position: relative;
}

.def-badge {
  background: #475aff;
  color: white;
  padding: 4px 12px;
  border-radius: 10px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  position: absolute;
  top: -10px;
  left: 20px;
}

.def-body {
  font-size: 18px;
  line-height: 1.6;
  color: #1d1d1f;
  font-style: italic;
}

@media (max-width: 768px) {
  .definition-card {
    padding: 20px;
    margin: 25px 0;
  }
  .def-body {
    font-size: 16px; 
  }
}

</style>