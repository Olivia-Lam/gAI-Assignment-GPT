function addPetRow() {
  const container = document.getElementById('petRows');
  if (!container) return;
  const idx = container.children.length;
  const row = document.createElement('div');
  row.className = 'row g-2 border rounded p-2 mb-2 bg-light';
  row.innerHTML = `
    <div class="col-md-3"><input class="form-control" name="pets[${idx}][pet_name]" placeholder="Pet name"></div>
    <div class="col-md-3"><input class="form-control" name="pets[${idx}][breed]" placeholder="Breed"></div>
    <div class="col-md-2"><input class="form-control" type="number" min="0" name="pets[${idx}][age]" placeholder="Age"></div>
    <div class="col-md-3"><input class="form-control" type="file" accept="image/*" name="pet_photos_${idx}"></div>
    <div class="col-md-1 d-grid"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()">×</button></div>`;
  container.appendChild(row);
}
