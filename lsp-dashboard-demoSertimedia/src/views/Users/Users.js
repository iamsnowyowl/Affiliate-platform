import React, { Component } from 'react';
import {
  listPermission,
} from '../../components/config/config';
import PagePermission from '../../components/PagePermission/PagePermission'
// import {SearchData} from '../../components/SearchTable/SearchData';

import 'antd/dist/antd.css';
import '../../css/TableAntd.css';
import '../../css/loaderDataTable.css';
import ListUsers from '../../components/ListTables/ListUsers';
// import style from '../../css/style.css';
class Users extends Component {
  render() {
    var item = "USER"
    return (
      <div className="animated fadeIn">
        {
          listPermission(item) === true ? (<ListUsers />) : (<PagePermission/> )
        }
      </div>
    );
  }
}

export default Users