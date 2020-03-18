import React from 'react';
import { Table, Input } from 'antd';

import 'antd/dist/antd.css';
import '../../css/TableAntd.css';
import '../../css/loaderDataTable.css';
import { multiLanguage } from '../../components/Language/getBahasa';

type Props = {
  data: any,
  pagination: {},
  columns: any,
  handleTableChange: any,
  getColumnSearchProps: any,
  handleSearch: any,
  handleChange: any,
  loading: Boolean
};

const Search = Input.Search;

class TableAlumni extends React.Component<Props> {
  render() {
    const {
      data,
      pagination,
      handleTableChange,
      columns,
      handleChange,
      handleSearch,
      loading
    } = this.props;
    return (
      <div>
        {`${multiLanguage.searching} `}
        <Search
          placeholder={multiLanguage.search}
          onSearch={handleSearch}
          onChange={handleChange}
          style={{ width: 310 }}
        />
        <p />
        <Table
          columns={columns}
          rowKey={record => record.user_id}
          dataSource={data}
          pagination={pagination}
          loading={loading}
          onChange={handleTableChange}
          striped
        />
      </div>
    );
  }
}

export default TableAlumni;
