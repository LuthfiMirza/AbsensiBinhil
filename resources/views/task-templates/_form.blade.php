@csrf
<div class="card">
    <div class="filter-grid">
        <div class="form-field" style="flex:1;min-width:260px;">
            <label>Nama Tugas</label>
            <input type="text" name="name" value="{{ old('name', $taskTemplate->name) }}" class="form-control" placeholder="Contoh: Sapu area lobby" required>
            @error('name')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-field">
            <label>Area Default</label>
            <input type="text" name="default_area" value="{{ old('default_area', $taskTemplate->default_area) }}" class="form-control" placeholder="Blok A / Lobby">
            @error('default_area')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-field">
            <label>Shift Default</label>
            <input type="text" name="default_shift" value="{{ old('default_shift', $taskTemplate->default_shift) }}" class="form-control" placeholder="pagi / siang">
            @error('default_shift')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-field" style="max-width:130px;">
            <label>Urutan</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $taskTemplate->sort_order ?? 0) }}" class="form-control">
            @error('sort_order')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="form-field" style="margin-top:16px;">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" style="min-height:110px;padding-top:12px;" placeholder="Instruksi singkat untuk petugas">{{ old('description', $taskTemplate->description) }}</textarea>
        @error('description')<p class="field-error">{{ $message }}</p>@enderror
    </div>
    <label style="display:flex;gap:10px;align-items:center;margin-top:16px;font-weight:700;">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $taskTemplate->is_active ?? true))>
        Aktif dan bisa dipakai untuk assign tugas
    </label>
    <div class="form-actions">
        <button class="btn-primary" type="submit">Simpan Master Tugas</button>
        <a href="{{ route('task-templates.index') }}" class="btn-secondary">Batal</a>
    </div>
</div>
