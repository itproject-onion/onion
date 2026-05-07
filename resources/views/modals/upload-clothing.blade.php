<!-- Button für das Upload-Modal -->
<button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
  <i class="bi bi-upload"></i> KLEIDUNGSSTÜCK HOCHLADEN
</button>

<!-- Upload-Modal mit Tag-Auswahl -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true"
     style="position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Bild hochladen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <div class="mb-3">
          <label for="clothingImage" class="form-label">Bild auswählen</label>
          <input type="file" class="form-control" id="clothingImage" accept="image/*" required>
        </div>
        <div class="text-center">
          <img id="imagePreview" src="#" alt="Vorschau" class="img-fluid rounded d-none" style="max-height: 300px; max-width: 100%;">
        </div>

       
        <div class="mt-3">
          <label class="form-label">Tags auswählen</label>
          <div id="uploadTagSelection" class="d-flex flex-wrap gap-2">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-primary" id="uploadButton">Hochladen</button>
      </div>
    </div>
  </div>
</div>