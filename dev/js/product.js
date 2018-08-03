class Product {
  static gl(id) {
    var keys = 'id.name.cost';

    var table = 'product';
    var limit = 100;

    var data = Data.gl(table, keys, limit);
    console.log(data);
  }

  static row(data) {

    // data
    var table = 'gallery';
    var RI = 'row';

    var dWrap = {
      id: 'wrap',
      class: 'col',
    }

    var dRow = {
      id: 'grow',
      class: 'row',
    }

    var i = 0;
    var j = 0;
    list.forEach(function(data){

      // default wrap id
      var wrapId = id;
      
      if (dWrap != 'undefined') {
        
        // wrap
        wrapId = dWrap['id'] + '_' + i; // wrap id
        var wrap = document.createElement('div');
        wrap.classList = dWrap['class'];
        wrap.id = wrapId;
        $('#' + id).append(wrap);
      }

      // row
      var rowId = dRow['id'] + '_' + i; // row id
      var row = document.createElement('div');
      row.classList = dRow['class'];
      row.id = rowId;
      $('#' + wrapId).append(row); // row append

      // cover
      var cover = '<div class="row__cover"><img src="/img/'+ table +'/'+ data['id'] + data['cover'] +'" class="row__img"></div>';
      $('#' + rowId).append(cover);
      
      // content
      var content = '<div class="row__content"><p class="row__title">'+ data['descrip'] +'</p></div>';
      $('#' + rowId).append(content);

      // edit footer
      var footer = '<div class="row__footer"><a href="/epage?table='+ table +'&id=' + data['id'] + '" class="button button_erow">Изменить</a> </div>';
      $('#' + rowId).append(footer);

      i++;
    });

    var arow = '<div class="col"><a href="apage?table='+ table +'" class="row row_add">Добавить</a><div>';
    $("#" + id).append(arow);
  }
  // gallery end
}

// data type
function dt(id, url, category, type) {

  switch (category) {
    case 'view': var url = '/views/' + url + '.html'; break;
  }

  $.ajax({
    url: url,
    type: 'POST',
    success: function(result){
      if (type == 'html') $('#' + id).html(result);
      else $('#' + id).append(result);
    }
  });
}

// insert data
function ib(table, keys, id) {

  // handler
  var url = '/gd';

  // str
  var str = "gd=1";

  // table
  str += '&table=' + table;
  str += '&id=' + id;

  // keys
  str += '&keys=';
  Object.keys(keys).forEach(function(key) {
    str += key + '.';
  });

  // delete last simvol
  str = str.substring(0, str.length - 1);

  $.ajax({
    url: url,
    type: 'POST',
    data: str,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    success: function(data){

      // data
      data = JSON.parse(data);
      ddi(keys, data);
    }
  });
}

// insert data in base
function idb(table, keys) {

  var str = "id=1";
  var url = '/id';

  str += '&table=' + table;
  str += '&keys=' + keys;

  keys = keys.split('.');
  keys.forEach(function(key) {
    var value = $('#' + key).val();
    if (!value) value = $('#' + key).html();

    str += '&' + key + '=' + value;
  });

  console.log(str);

  $.ajax({
    url: url,
    type: 'POST',
    data: str,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    success: function(result){
      location.reload(true);
    }
  });
}

// update the data
function ud(table, data, id) {
  // handler
  var str = "ud=1";
  var keys = '';

  // data foreach
  Object.keys(data).forEach(function(key) {

    // add post data
    var eId = data[key];
    var value = $('#' + eId).html();
    str += '&' + key + '=' + value;

    // keys
    keys += key + '.';
  });

  // delete last simvol
  keys = keys.substring(0, keys.length - 1);

  // id
  str += "&keys=" + keys;
  str += '&id=' + id;
  str += '&table=' + table;

  str = str.replace(/\&nbsp;/g, " ");
  
  $.ajax({
    url: '/ud',
    type: 'POST',
    data: str,
    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    success: function(result){
      location.reload(true);
    }
  });
}

// arrange by structure
class abs {

  // advantages structure
  static advan(id, list) {

    var i = 0;
    var j = 0;
    list.forEach(function(data){
      
      // wrap
      var wrap = document.createElement('div');
      wrap.classList = 'col';
      wrap.id = 'dwrap_' + i;
      $('#' + id).append(wrap);


      // row id
      var rowId = 'drow_' + i;  

      // row    
      var row = document.createElement('div');
      row.classList = 'row';
      row.id = rowId;
      // append
      $('#dwrap_' + i).append(row);


      // row id
      rowId = '#' + rowId;

      // cover
      var cover = '<div class="row__cover"><img src="/img/advan/'+ data['id'] + data['icon'] +'" class="row__img"></div>';
      $(rowId).append(cover);
      
      // content
      var content = '<div class="row__content"><p class="row__title">'+ data['title'] +'</p></div>';
      $(rowId).append(content);

      // edit footer
      var footer = '<div class="row__footer"><a href="/epage?table=advan&id=' + data['id'] + '" class="button button_erow">Изменить</a> </div>';
      $(rowId).append(footer);

      i++;
    });

    var arow = '<div class="col"><a href="galery?act=add" class="row row_add">Добавить</a><div>';
    $("#" + id).append(arow);
  }
  // galery end

  // course structure
  static course(id, list) {

    var i = 0;
    var j = 0;
    list.forEach(function(data){
      
      // wrap
      var wrap = document.createElement('div');
      wrap.classList = 'col';
      wrap.id = 'dwrap_' + i;
      $('#' + id).append(wrap);


      // row id
      var rowId = 'drow_' + i;  

      // row    
      var row = document.createElement('div');
      row.classList = 'row';
      row.id = rowId;
      // append
      $('#dwrap_' + i).append(row);


      // row id
      rowId = '#' + rowId;

      // cover
      var cover = '<div class="row__cover"><img src="/img/course/'+ data['id'] + data['icon'] +'" class="row__img"></div>';
      $(rowId).append(cover);
      
      // content
      var content = '<div class="row__content"><p class="row__title">'+ data['title'] +'</p><p class="row__descrip">'+ data['descrip'] +'</p></div>';
      $(rowId).append(content);

      // edit footer
      var footer = '<div class="row__footer"><a href="/epage?table=course&id=' + data['id'] + '" class="button button_erow">Изменить</a> </div>';
      $(rowId).append(footer);

      i++;
    });

    var arow = '<div class="col"><a href="galery?act=add" class="row row_add">Добавить</a><div>';
    $("#" + id).append(arow);
  }
  // course end

  // review structure
  static review(id, list) {

    // data
    var table = 'review';
    var RI = 'row';

    var i = 0;
    var j = 0;
    list.forEach(function(data){
      
      // wrap
      var wrap = document.createElement('div');
      wrap.classList = 'col';
      wrap.id = 'dwrap_' + i;
      $('#' + id).append(wrap);

      // row id
      var rowId = RI + '_' + i;

      // row    
      var row = document.createElement('div');
      row.classList = 'row';
      row.id = rowId;
      // append
      $('#dwrap_' + i).append(row);


      // cover
      var cover = '<div class="row__cover"><img src="/img/'+ table +'/'+ data['id'] + data['avatar'] +'" class="row__img"></div>';
      $('#' + rowId).append(cover);
      
      // content
      var content = '<div class="row__content"><p class="row__title">'+ data['name'] +'</p><p class="row__descrip">'+ data['position'] +'</p></div>';
      $('#' + rowId).append(content);

      // edit footer
      var footer = '<div class="row__footer"><a href="/epage?table='+ table +'&id=' + data['id'] + '" class="button button_erow">Изменить</a> </div>';
      $('#' + rowId).append(footer);

      i++;
    });

    var arow = '<div class="col"><a href="apage?table='+ table +'" class="row row_add">Добавить</a><div>';
    $("#" + id).append(arow);
  }
  // review end


  // company structure
  static company(id, list) {

    // data
    var table = 'company';
    var RI = 'row';

    var i = 0;
    var j = 0;
    list.forEach(function(data){
      
      // wrap
      var wrap = document.createElement('div');
      wrap.classList = 'col';
      wrap.id = 'dwrap_' + i;
      $('#' + id).append(wrap);

      // row id
      var rowId = RI + '_' + i;

      // row    
      var row = document.createElement('div');
      row.classList = 'row';
      row.id = rowId;
      // append
      $('#dwrap_' + i).append(row);


      // cover
      var cover = '<div class="row__cover"><img src="/img/'+ table +'/'+ data['id'] + data['logo'] +'" class="row__img"></div>';
      $('#' + rowId).append(cover);
      
      // content
      var content = '<div class="row__content"><p class="row__title">'+ data['name'] +'</p></div>';
      $('#' + rowId).append(content);

      // edit footer
      var footer = '<div class="row__footer"><a href="/epage?table='+ table +'&id=' + data['id'] + '" class="button button_erow">Изменить</a> </div>';
      $('#' + rowId).append(footer);

      i++;
    });

    var arow = '<div class="col"><a href="apage?table='+ table +'" class="row row_add">Добавить</a><div>';
    $("#" + id).append(arow);
  }
  // review end

  // gallery structure
  static gallery(id, list) {

    // data
    var table = 'gallery';
    var RI = 'row';

    var dWrap = {
      id: 'wrap',
      class: 'col',
    }

    var dRow = {
      id: 'grow',
      class: 'row',
    }

    var i = 0;
    var j = 0;
    list.forEach(function(data){

      // default wrap id
      var wrapId = id;
      
      if (dWrap != 'undefined') {
        
        // wrap
        wrapId = dWrap['id'] + '_' + i; // wrap id
        var wrap = document.createElement('div');
        wrap.classList = dWrap['class'];
        wrap.id = wrapId;
        $('#' + id).append(wrap);
      }

      // row
      var rowId = dRow['id'] + '_' + i; // row id
      var row = document.createElement('div');
      row.classList = dRow['class'];
      row.id = rowId;
      $('#' + wrapId).append(row); // row append

      // cover
      var cover = '<div class="row__cover"><img src="/img/'+ table +'/'+ data['id'] + data['cover'] +'" class="row__img"></div>';
      $('#' + rowId).append(cover);
      
      // content
      var content = '<div class="row__content"><p class="row__title">'+ data['descrip'] +'</p></div>';
      $('#' + rowId).append(content);

      // edit footer
      var footer = '<div class="row__footer"><a href="/epage?table='+ table +'&id=' + data['id'] + '" class="button button_erow">Изменить</a> </div>';
      $('#' + rowId).append(footer);

      i++;
    });

    var arow = '<div class="col"><a href="apage?table='+ table +'" class="row row_add">Добавить</a><div>';
    $("#" + id).append(arow);
  }
  // gallery end

  // client
  static client(id, list) {
    list.forEach(function(data){
      var content = '<tr> <td>' + data['id'] + '</td><td> <p class="name">' + data['name'] + '</p></td><td> <a href="callto:996776874647" class="link link_phone">' + data['phone'] + '</a> </td><td> <a href="mailto:" class="link link_mail">' + data['mail'] + '</a> </td><td> <p class="text_date">' + data['dr'] + '</p></td></tr>';
      $('#' + id).append(content);
    });
  }
}

class mwbs {
  static course(id, data) {
    var header = '<div class="mw__header"> <p class="section__title">курс</p><h2 class="section__headline">Ораторское искусство</h2> <a class="mw__close" onClick="MW.Hide();"> <i class="fa fa-times"></i> </a></div>';

    var content = '<div class="mw__content"> <p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione alias vero sed, odio quisquam doloribus minima deleniti facilis, illo voluptatem corporis eos, vel accusamus. Numquam dolorem similique tenetur laudantium omnis!</p></div>';

    var footer = '<div class="mw__footer"> <a href="/oratory" class="button fl_l">Подробнее</a> <a class="button fl_r" onClick="MW.Go(\'book\');">Записаться</a></div>';
  }
}

// distribute data by id
function ddi(keys, data) {

  Object.keys(keys).forEach(function(key) {

    if (key == 'mn') $('#' + keys[key]).attr("href", '/' + data[0][key]);
    else $('#' + keys[key]).append(data[key]);
  });

}

// check is not a contract
function ccon(url) {

  $.ajax({
  url: '/' + url,
  type: 'HEAD',
  error:
    function(){
      return false;
    },
  success:
    function(){
      return true;
    }
  });
}