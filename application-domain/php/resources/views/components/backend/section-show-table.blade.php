


Testing
<table class="table table-responsive-sm table-hover table-bordered">
    <?php
    $all_columns = $data->getTableColumns();

    ?>

    <thead>
        <tr>
            <th scope="col">
                <strong>
                    @lang("Name")
                </strong>
            </th>
            <th scope="col">
                <strong>
                    @lang("Value")
                </strong>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($all_columns as $column)
            <tr>
                <td>
                    <strong>

                    </strong>
                </td>
                <td>
                    {!! ($column) !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Lightbox2 Library --}}
<x-library.lightbox />
