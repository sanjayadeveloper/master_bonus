//tooltip mouseover PDF / xsl
$(function () {
   $('[data-toggle="tooltip"]').tooltip()
});
// Table column filter dynamic 
$(document).ready(function () {
  //GET ALL COLUMN NAMES
  let th = $('#listing_tableID th').map(function () {
      return $(this).text();
  }).get();
  // APPEND COLUMN NAMES TO FILTER
  $('.table_column_filter .toggles').append(
      $.map(th, function (ele) {
          return `<label>
                      <input type="checkbox" id="${ele}" value="${ele}">
                      <span>${ele}</span>
                  </label>`;
      }).join('')
  );
  // CHECK ALL CHECKBOXES WHEN PAGE LOAD
  $('.table_column_filter .toggles :checkbox').prop('checked', true);

  let $form = $('.table_column_filter .toggles :checkbox');
  function toggleColumn(event) {
    let value = event.target.value;
    let status = event.target.checked;
    // SHOW OR HIDE COLUMN ACCORDING TO CHECKBOX CHECKED OR NOT
    if (value == 'all') {
      if (status){
        $('#listing_tableID td, #listing_tableID th').show();
        $('.table_column_filter .toggles :checkbox').prop('checked', true);
      } else {
        $('#listing_tableID td, #listing_tableID th').hide();
        $('.table_column_filter .toggles :checkbox').prop('checked', false);
      }
      $('#listing_tableID td, #listing_tableID th').toggle(status);
    } else {
      $('#listing_tableID td:nth-child(' + (th.indexOf(value) + 1) + '), #listing_tableID th:nth-child(' + (th.indexOf(value) + 1) + ')').toggle();
    }
  }
  $form.on('change', toggleColumn);
});

$(".table_filtericon").click("on", function(){
    $(".table_column_filter").toggleClass("open");
});

//******************************************

$(document).ready(function () {
  //GET ALL COLUMN NAMES
  let th = $('#listing_tableID1 th').map(function () {
      return $(this).text();
  }).get();
  // APPEND COLUMN NAMES TO FILTER
  $('.table_column_filter1 .toggles').append(
      $.map(th, function (ele) {
          return `<label>
                      <input type="checkbox" id="${ele}" value="${ele}">
                      <span>${ele}</span>
                  </label>`;
      }).join('')
  );
  // CHECK ALL CHECKBOXES WHEN PAGE LOAD
  $('.table_column_filter1 .toggles :checkbox').prop('checked', true);

  let $form = $('.table_column_filter1 .toggles :checkbox');
  function toggleColumn1(event) {
    let value = event.target.value;
    let status = event.target.checked;
    // SHOW OR HIDE COLUMN ACCORDING TO CHECKBOX CHECKED OR NOT
    if (value == 'all') {
      if (status){
        $('#listing_tableID1 td, #listing_tableID1 th').show();
        $('.table_column_filter1 .toggles :checkbox').prop('checked', true);
      } else {
        $('#listing_tableID1 td, #listing_tableID1 th').hide();
        $('.table_column_filter1 .toggles :checkbox').prop('checked', false);
      }
      $('#listing_tableID1 td, #listing_tableID1 th').toggle(status);
    } else {
      $('#listing_tableID1 td:nth-child(' + (th.indexOf(value) + 1) + '), #listing_tableID1 th:nth-child(' + (th.indexOf(value) + 1) + ')').toggle();
    }
  }
  $form.on('change', toggleColumn1);
});
$(".table_filtericon1").click("on", function(){
    $(".table_column_filter1").toggleClass("open");
});

//******************************************

$(document).ready(function () {
  //GET ALL COLUMN NAMES
  let th = $('#listing_tableID2 th').map(function () {
      return $(this).text();
  }).get();
  // APPEND COLUMN NAMES TO FILTER
  $('.table_column_filter2 .toggles').append(
      $.map(th, function (ele) {
          return `<label>
                      <input type="checkbox" id="${ele}" value="${ele}">
                      <span>${ele}</span>
                  </label>`;
      }).join('')
  );
  // CHECK ALL CHECKBOXES WHEN PAGE LOAD
  $('.table_column_filter2 .toggles :checkbox').prop('checked', true);

  let $form = $('.table_column_filter2 .toggles :checkbox');
  function toggleColumn2(event) {
    let value = event.target.value;
    let status = event.target.checked;
    // SHOW OR HIDE COLUMN ACCORDING TO CHECKBOX CHECKED OR NOT
    if (value == 'all') {
      if (status){
        $('#listing_tableID2 td, #listing_tableID2 th').show();
        $('.table_column_filter2 .toggles :checkbox').prop('checked', true);
      } else {
        $('#listing_tableID2 td, #listing_tableID2 th').hide();
        $('.table_column_filter2 .toggles :checkbox').prop('checked', false);
      }
      $('#listing_tableID2 td, #listing_tableID2 th').toggle(status);
    } else {
      $('#listing_tableID2 td:nth-child(' + (th.indexOf(value) + 1) + '), #listing_tableID2 th:nth-child(' + (th.indexOf(value) + 1) + ')').toggle();
    }
  }
  $form.on('change', toggleColumn2);
});
$(".table_filtericon2").click("on", function(){
    $(".table_column_filter2").toggleClass("open");
});

//******************************************

$(document).ready(function () {
  //GET ALL COLUMN NAMES
  let th = $('#listing_tableID3 th').map(function () {
      return $(this).text();
  }).get();
  // APPEND COLUMN NAMES TO FILTER
  $('.table_column_filter3 .toggles').append(
      $.map(th, function (ele) {
          return `<label>
                      <input type="checkbox" id="${ele}" value="${ele}">
                      <span>${ele}</span>
                  </label>`;
      }).join('')
  );
  // CHECK ALL CHECKBOXES WHEN PAGE LOAD
  $('.table_column_filter3 .toggles :checkbox').prop('checked', true);

  let $form = $('.table_column_filter3 .toggles :checkbox');
  function toggleColumn3(event) {
    let value = event.target.value;
    let status = event.target.checked;
    // SHOW OR HIDE COLUMN ACCORDING TO CHECKBOX CHECKED OR NOT
    if (value == 'all') {
      if (status){
        $('#listing_tableID3 td, #listing_tableID3 th').show();
        $('.table_column_filter3 .toggles :checkbox').prop('checked', true);
      } else {
        $('#listing_tableID3 td, #listing_tableID3 th').hide();
        $('.table_column_filter3 .toggles :checkbox').prop('checked', false);
      }
      $('#listing_tableID3 td, #listing_tableID3 th').toggle(status);
    } else {
      $('#listing_tableID3 td:nth-child(' + (th.indexOf(value) + 1) + '), #listing_tableID3 th:nth-child(' + (th.indexOf(value) + 1) + ')').toggle();
    }
  }
  $form.on('change', toggleColumn3);
});
$(".table_filtericon3").click("on", function(){
    $(".table_column_filter3").toggleClass("open");
});

//******************************************

$(document).ready(function () {
  //GET ALL COLUMN NAMES
  let th = $('#listing_tableID4 th').map(function () {
      return $(this).text();
  }).get();
  // APPEND COLUMN NAMES TO FILTER
  $('.table_column_filter4 .toggles').append(
      $.map(th, function (ele) {
          return `<label>
                      <input type="checkbox" id="${ele}" value="${ele}">
                      <span>${ele}</span>
                  </label>`;
      }).join('')
  );
  // CHECK ALL CHECKBOXES WHEN PAGE LOAD
  $('.table_column_filter4 .toggles :checkbox').prop('checked', true);

  let $form = $('.table_column_filter4 .toggles :checkbox');
  function toggleColumn4(event) {
    let value = event.target.value;
    let status = event.target.checked;
    // SHOW OR HIDE COLUMN ACCORDING TO CHECKBOX CHECKED OR NOT
    if (value == 'all') {
      if (status){
        $('#listing_tableID4 td, #listing_tableID4 th').show();
        $('.table_column_filter4 .toggles :checkbox').prop('checked', true);
      } else {
        $('#listing_tableID4 td, #listing_tableID4 th').hide();
        $('.table_column_filter4 .toggles :checkbox').prop('checked', false);
      }
      $('#listing_tableID4 td, #listing_tableID4 th').toggle(status);
    } else {
      $('#listing_tableID4 td:nth-child(' + (th.indexOf(value) + 1) + '), #listing_tableID4 th:nth-child(' + (th.indexOf(value) + 1) + ')').toggle();
    }
  }
  $form.on('change', toggleColumn4);
});
$(".table_filtericon4").click("on", function(){
    $(".table_column_filter4").toggleClass("open");
});

//******************************************

$(document).ready(function () {
  //GET ALL COLUMN NAMES
  let th = $('#listing_tableID5 th').map(function () {
      return $(this).text();
  }).get();
  // APPEND COLUMN NAMES TO FILTER
  $('.table_column_filter5 .toggles').append(
      $.map(th, function (ele) {
          return `<label>
                      <input type="checkbox" id="${ele}" value="${ele}">
                      <span>${ele}</span>
                  </label>`;
      }).join('')
  );
  // CHECK ALL CHECKBOXES WHEN PAGE LOAD
  $('.table_column_filter5 .toggles :checkbox').prop('checked', true);

  let $form = $('.table_column_filter5 .toggles :checkbox');
  function toggleColumn5(event) {
    let value = event.target.value;
    let status = event.target.checked;
    // SHOW OR HIDE COLUMN ACCORDING TO CHECKBOX CHECKED OR NOT
    if (value == 'all') {
      if (status){
        $('#listing_tableID5 td, #listing_tableID5 th').show();
        $('.table_column_filter5 .toggles :checkbox').prop('checked', true);
      } else {
        $('#listing_tableID5 td, #listing_tableID5 th').hide();
        $('.table_column_filter5 .toggles :checkbox').prop('checked', false);
      }
      $('#listing_tableID5 td, #listing_tableID5 th').toggle(status);
    } else {
      $('#listing_tableID5 td:nth-child(' + (th.indexOf(value) + 1) + '), #listing_tableID5 th:nth-child(' + (th.indexOf(value) + 1) + ')').toggle();
    }
  }
  $form.on('change', toggleColumn5);
});
$(".table_filtericon5").click("on", function(){
    $(".table_column_filter5").toggleClass("open");
});