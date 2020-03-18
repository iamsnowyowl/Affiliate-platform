import React, { Component } from "react";
import { Link } from "react-router-dom";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Input, Icon, Table, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import {
  path_subSchema,
  baseUrl,
  path_schema,
  path_schemaViews,
  getData,
  createPermission
} from "../../../components/config/config";
import { Digest } from "../../../containers/Helpers/digest";
import { multiLanguage } from "../../../components/Language/getBahasa";

// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../../css/TableAntd.css";
import "../../../css/loaderDataTable.css";
import ButtonDelete from "../../../components/Button/ButtonDelete";
// import style from '../../css/style.css';

const Search = Input.Search;
const columns = [
  {
    key: "sub_schema_number",
    title: (
      <h5 style={{ fontWeight: "bold" }}>{multiLanguage.subSchemaCode}</h5>
    ),
    dataIndex: "sub_schema_number",
    sorter: true
  },
  {
    key: "sub_schema_name",
    title: (
      <h5 style={{ fontWeight: "bold" }}>{multiLanguage.subSchemaName}</h5>
    ),
    dataIndex: "sub_schema_name",
    sorter: true,
    width: "30%"
  },
  {
    key: "schema_id",
    title: (
      <h5 style={{ fontWeight: "bold" }}>{multiLanguage.mainSchemaName}</h5>
    ),
    sorter: true,
    dataIndex: "schema_id",
    width: "20%",
    render: (value, row) => {
      return row.schema_name;
    }
  },
  {
    key: "sub_schema_id",
    title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
    dataIndex: "sub_schema_id",
    width: "20%",
    render: (value, row) => {
      const id_Schema = row.schema_id;
      return (
        <div>
          <a
            href={
              "/Schema/" +
              id_Schema +
              "/sub_schemas/" +
              value +
              "/" +
              row.sub_schema_number
            }
            className="btn btn-success col-md-auto"
            title={multiLanguage.Edit}
          >
            <i className="fa fa-edit" />
          </a>{" "}
          <ButtonDelete
            id_delete={row.sub_schema_number}
            path={path_schema + "/" + id_Schema + path_subSchema}
          />
        </div>
      );
    }
  }
];
const columnsChild = [
  {
    key: "unit_code",
    title: (
      <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
        {multiLanguage.codeUnit}
      </h5>
    ),
    dataIndex: "unit_code"
  },
  {
    key: "title",
    title: (
      <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
        Nama Unit Kompetensi
      </h5>
    ),
    dataIndex: "title"
  }
];
const tableProps = {
  expandedRowRender: record => (
    <Table
      rowKey={record => record.unit_competence_id}
      columns={columnsChild}
      dataSource={record.children}
      pagination={false}
    />
  )
};

class SubSchema extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      visible: false,
      payload: [],
      payloadMainSchema: [],
      payloadUnit: []
    };
  }

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleSearch = searchText => {
    this.setState({ loading: true });
    const auth = Digest("/public" + path_schemaViews + "/tree", "GET");
    reqwest({
      url:
        baseUrl +
        "/public" +
        path_schemaViews +
        "/tree" +
        "?search=" +
        searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = {}) => {
    this.setState({ loading: true });
    const auth = Digest("/public" + path_schemaViews + "/tree", "GET");
    reqwest({
      url: baseUrl + "/public" + path_schemaViews + "/tree",
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        console.log("response sub schema", response.data);
        const pagination = { ...this.state.pagination };
        const count = parseInt(response.count, 10);
        pagination.total = count;

        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })
      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        } else if (error.status === 419) {
          this.errorTrial();
        }
      });
  };

  errorTrial = () => {
    Modal.error({
      title: "Pesan Error",
      content:
        "Masa trial anda telah habis,Harap menghubungi Admin NAS untuk info lebih lanjut",
      onOk() {
        localStorage.clear();
        window.location.replace("/login");
      }
    });
  };

  componentDidMount() {
    this.get();
    Axios(getData(path_schema, "GET")).then(response => {
      this.setState({
        payloadMainSchema: response.data.data
      });
    });
  }

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }

    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  expandUnit = record => {
    console.log("record", record);
    return <Button>edit</Button>;
  };

  render() {
    console.log("pagination", this.state.pagination);
    var item = "DEPARTMENT";
    const { payload, data, pagination, loading } = this.state;
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>
            <Row>
              <Col md="6">
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  {multiLanguage.listSubSchema}
                </h5>
              </Col>
              {createPermission(item) === true ? (
                <Col md="6" className="mb-3 mb-xl-0">
                  <Link to={"/schema/sub-schema/add-subSchema"}>
                    <Button className="float-md-right" color="primary">
                      <i className="fa fa-plus" />{" "}
                      {` ${multiLanguage.add} ${multiLanguage.subSchema}`}
                    </Button>
                  </Link>
                </Col>
              ) : (
                ""
              )}
            </Row>
          </CardHeader>
          <CardBody>
            {`${multiLanguage.searching} `}
            <Search
              placeholder={multiLanguage.search}
              onSearch={this.handleSearch}
              onChange={this.handleChange}
              style={{ width: 310 }}
            />{" "}
            <p />
            <Table
              columns={columns}
              rowKey="sub_schema_id"
              {...tableProps}
              rowClassName={record =>
                record.children === undefined ||
                record.children.length === undefined
                  ? "hide-expand"
                  : ""
              }
              onExpand={this.expandUnit}
              dataSource={data}
              pagination={pagination}
              loading={loading}
              onChange={this.handleTableChange}
              striped
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default SubSchema;
