.toggles {
        display: flex;
        flex-direction: column;
        position: absolute;
        background: #efefef;
        min-width: 200px;
        padding: 10px 20px;
        border-radius: 10px;
        right: 0;
        box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.1);
    }
    .table_column_filter{
        right: 0;
        padding: 0;
        box-shadow: unset;
        border: unset;
        top: 60px;
        position: absolute;
        z-index: 9999;
        display: none;
    }
  .table_column_filter.open{
        display:block;
    }

    /* #listing_tableID th,
    #listing_tableID td {
        display: none;
    } */

    #listing_tableID th.actv,
    #listing_tableID td.actv {
        display: table-cell;
    }
    /**.table_column_filter */
    .table_column_filter .toggles input {
        margin: 0;
        margin-right: 10px;
    }
    .table_column_filter .toggles span {
      font-size: 12px;
    }