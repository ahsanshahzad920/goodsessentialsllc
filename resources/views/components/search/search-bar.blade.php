<x-forms.form :action="route($route)" method="GET" class="d-flex mb-4" role="search">
    <x-forms.field type="search" name="search" id="search" value="{{ request()->search ?? '' }}"
        placeholder="Search...." />
    <button class="btn btn-primary" type="submit">
        {{ __('Search') }}
    </button>
</x-forms.form>
