import React, { Component } from "react";
import { Card, CardHeader, CardBody, Row, Col, Button } from "reactstrap";
import { Input, Icon, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import FileBase64 from "react-file-base64";

import { baseUrl, path_alumni } from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import TableAlumni from "./TableAlumni";
import { multiLanguage } from "../../components/Language/getBahasa";
import LoadingOverlay from "react-loading-overlay";
import { AvForm,AvGroup } from "availity-reactstrap-validation";
import LabelRequired from "../../components/Label/LabelRequired";

import "../../css/loaderDataTable.css";

export default class Alumni extends Component {
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
      file_name: "",
      base64: ""
    };
  }

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div
        style={{
          padding: 8
        }}
      >
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
          style={{
            width: 188,
            marginBottom: 8,
            display: "block"
          }}
        />{" "}
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{
            width: 90,
            marginRight: 8
          }}
        >
          {multiLanguage.search}{" "}
        </Button>{" "}
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{
            width: 90
          }}
        >
          {multiLanguage.reset}{" "}
        </Button>{" "}
      </div>
    ),
    filterIcon: filtered => (
      <Icon
        type="search"
        style={{
          color: filtered ? "#1890ff" : undefined
        }}
      />
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
        highlightStyle={{
          backgroundColor: "#ffc069",
          padding: 0
        }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleSearch = searchText => {
    this.setState({
      loading: true
    });
    const auth = Digest(path_alumni, "GET");
    reqwest({
      url: baseUrl + path_alumni + "?search=" + searchText,
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
      const pagination = {
        ...this.state.pagination
      };
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
    this.setState({
      searchText: ""
    });
  };

  get = (params = {}) => {
    this.setState({ loading: true });
    const auth = Digest(path_alumni, "GET");
    reqwest({
      url: baseUrl + path_alumni,
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

  componentDidMount() {
    this.get();
  }

  handleTableChange = pagination => {
    const pager = {
      ...this.state.pagination
    };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";

    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
    });
  };
  files;

  getFiles(files) {
    console.log(files);
    this.setState({ base64: files.base64, file_name: files.file.name });
  }

  handleSubmit = (event, errors, values) => {
    this.setState({ errors, values, loading: true });
    event.preventDefault();

    var data = {};
    // var formData = new FormData();
    data["file_name"] = this.state.file_name;
    data["base64"] = this.state.base64;
    console.log(data);
    // formData.append('file_import', this.state.files);
  };

  showModal = () => {
    this.setState({
      visible: true
    });
  };

  handleCancel = () => {
    this.setState({
      visible: false
    });
  };

  render() {
    const { data, pagination, loading } = this.state;
    const columns = [
      {
        key: "alumni_name",
        dataIndex: "alumni_name",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.name}</h5>,
        sorter: true,
        render: value => {
          return value;
        }
      },
      {
        key: "contact",
        dataIndex: "contact",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.contact}</h5>,
        render: value => {
          return value;
        }
      },
      {
        key: "email",
        dataIndex: "email",
        title: <h5 style={{ fontWeight: "bold" }}>{"Email"}</h5>,
        render: value => {
          return value;
        }
      },
      {
        key: "competence",
        dataIndex: "competence",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.schema}</h5>,
        sorter: true,
        render: value => {
          return value;
        }
      },
      {
        key: "register_number",
        dataIndex: "register_number",
        title: <h5 style={{ fontWeight: "bold" }}>{"No.Reg"}</h5>,
        sorter: true,
        render: value => {
          return value;
        }
      },
      {
        key: "certificate_number",
        dataIndex: "certificate_number",
        title: (
          <h5
            style={{ fontWeight: "bold" }}
          >{`No ${multiLanguage.certificate}`}</h5>
        ),
        sorter: true,
        render: value => {
          return value;
        }
      },
      {
        key: "tuk",
        dataIndex: "tuk",
        title: <h5 style={{ fontWeight: "bold" }}>{"TUK"}</h5>,
        sorter: true,
        render: value => {
          return value;
        }
      },
      {
        key: "nik",
        dataIndex: "nik",
        title: <h5 style={{ fontWeight: "bold" }}>{"No NIK"}</h5>,
        sorter: true,
        render: () => {
          return "Belum Ada Di DataBase";
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <Modal
          title="Import Data Alumni"
          visible={this.state.visible}
          onOk={this.handleSubmit}
          onCancel={this.handleCancel}
        >
          <LoadingOverlay active={this.state.overlay} spinner text="Loading">
            <AvForm>
              <AvGroup row>
              <Col>
                <LabelRequired fors="file_import" label="File" />
              </Col>
              <Col className="fileContainer">
                <Button className="btn">
                  {" "}
                  <i className="fa fa-search" /> {multiLanguage.search} File
                </Button>
                <FileBase64
                  multiple={false}
                  onDone={this.getFiles.bind(this)}
                /><p style={{marginBottom:'0px'}} />
                <span className="required">*</span>
                <span className="label-sizefile">max File 6 MB</span>
              </Col>

              </AvGroup>
            </AvForm>
          </LoadingOverlay>
        </Modal>
        <Card>
          <CardHeader>
            <Row>
              <Col md="6">
                {" "}
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  {multiLanguage.list} Database {multiLanguage.asesi}
                </h5>
              </Col>
              <Col md="6" className="mb-3 mb-xl-0">
                <Button
                  className="float-md-right"
                  size="default"
                  color="primary"
                  onClick={this.showModal}
                >
                  <i className="fa fa-plus" />{" "}
                  {" " +
                    multiLanguage.add +
                    " " +
                    "Database " +
                    multiLanguage.asesi}{" "}
                </Button>
              </Col>{" "}
            </Row>{" "}
          </CardHeader>{" "}
          <CardBody>
            <TableAlumni
              data={data}
              pagination={pagination}
              columns={columns}
              loading={loading}
              handleTableChange={this.handleTableChange}
              handleChange={this.handleChange}
              handleSearch={this.handleSearch}
            />{" "}
          </CardBody>{" "}
        </Card>{" "}
      </div>
    );
  }
}
