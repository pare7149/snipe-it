<div id="{{ (isset($id_divname)) ? $id_divname : 'assignedAccessoriesBulkEditToolbar' }}" style="min-width:400px">
    <form
    method="POST"
    action="{{ route('accessories/bulkedit') }}"
    accept-charset="UTF-8"
    class="form-inline"
    id="{{ (isset($id_formname)) ? $id_formname : 'assignedAccessoriesBulkForm' }}"
>
    @csrf

    {{-- The sort and order will only be used if the cookie is actually empty (like on first-use) --}}
    <input name="sort" type="hidden" value="assets.id">
    <input name="order" type="hidden" value="asc">
    <label for="bulk_actions">
        <span class="sr-only">
            {{ trans('button.bulk_actions') }}
        </span>
    </label>
    <select name="bulk_actions" class="form-control select2" aria-label="bulk_actions" style="min-width: 350px;">
        @can('update', \App\Models\Accessory::class)
            <option value="update">Aktualisieren</option>
        @endcan
        @can('checkin', \App\Models\Accessory::class)
            <option value="checkin">Massen-Rücknahme</option>
        @endcan
    </select>

    <button class="btn btn-primary" id="{{ (isset($id_button)) ? $id_button : 'assignedAccessoriesBulkEditButton' }}" disabled>{{ trans('button.go') }}</button>
    </form>
</div>
