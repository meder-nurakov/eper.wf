class Data {
  static gl(table, keys, limit) {

    // handler
    var str = url + "=1";
    var url = '/gl';

    if (limit) str += '&limit=' + limit;

    str += '&keys=' + keys;
    str += '&table=' + table;
    
    $.ajax({
      url: url,
      type: 'POST',
      data: str,
      contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
      success: function(data){
        data = JSON.parse(data);

        return data;
      }
    });
  }
}