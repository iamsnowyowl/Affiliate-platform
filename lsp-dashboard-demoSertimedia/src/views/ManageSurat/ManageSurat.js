import React, { Component } from "react";
import {
  Row,
  Col,
  Card,
  CardHeader,
  CardBody,
  Modal,
  ModalHeader,
  ModalBody,
  ModalFooter,
  Button
} from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import FileBase64 from "react-file-base64";
import FileSaver from "file-saver";
import LoadingOverlay from "react-loading-overlay";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";

import {
  path_manageSurat,
  baseUrl,
  formatDate,
  getData,
  downloadFile
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import "../../css/Button.css";
import Axios from "axios";

const Search = Input.Search;
class ManageSurat extends Component {
  constructor(props) {
    super(props);
    this.state = {
      pagination: {},
      loading: false,
      modal: false,
      files: [],
      data: [],
      offset: 0,
      filteredInfo: null,
      searchText: "",
      letter_label: "",
      letter_id: ""
    };
  }

  toggle = row => {
    this.setState({
      modal: !this.state.modal,
      letter_label: row.letter_lable,
      letter_id: row.letter_id
    });
  };

  hideAlert = () => {
    this.setState({
      hideAlert: !this.state.hideAlert
    });
  };

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
    const auth = Digest(path_manageSurat, "GET");
    reqwest({
      url: baseUrl + path_manageSurat + "?search=" + searchText,
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
    const auth = Digest(path_manageSurat, "GET");
    reqwest({
      url: baseUrl + path_manageSurat,
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
        }
      });
  };

  getFiles(files) {
    console.log(files);

    this.setState({
      files: files
    });
  }

  componentDidMount() {
    this.get();
  }

  uploadFile = () => {
    this.setState({
      loading: true,
      modal: !this.state.modal
    });
    const path = path_manageSurat + "/" + this.state.letter_id;
    const data = {};
    data["letter_lable"] = this.state.letter_label;
    data["mime_type"] = this.state.files.type;
    data["file"] = this.state.files.base64;
    data["filename"] = this.state.files.name;

    Axios(getData(path, "PUT", data))
      .then(response => {
        window.location.reload();
      })
      .catch(error => {
        if (error) {
          this.setState({
            loading: false
          });
          NotificationManager.error(
            "Terjadi Kesalahan Meng upload surat mohon ulang kembali",
            "Error",
            5000
          );
        }
      });
  };

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

  download = row => {
    const path = path_manageSurat + "/" + row.letter_id + "/download";
    const name = row.filename;
    Axios(downloadFile(path, "GET")).then(response => {
      FileSaver.saveAs(new Blob([response.data]), name);
    });
  };

  render() {
    const columns = [
      {
        key: "letter_lable",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.letterName}</h5>
        ),
        dataIndex: "letter_lable",
        width: "20%",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "modified_date",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.lastUpdate}</h5>
        ),
        dataIndex: "modified_date",
        width: "15%",
        render: value => {
          return <div>{formatDate(value)}</div>;
        }
      },
      {
        key: "mime_type",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.typeFile}</h5>,
        dataIndex: "mime_type",
        width: "10%",
        render: value => {
          return (
            <div>
              {value ===
              "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                ? multiLanguage.document
                : "Excel"}
            </div>
          );
        }
      },
      {
        key: "letter_id",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        dataIndex: "letter_id",
        width: "15%",
        render: (value, row) => {
          return (
            <div>
              <Modal isOpen={this.state.modal} toggle={this.toggle}>
                <ModalHeader toggle={this.toggle}>
                  {multiLanguage.upload}
                </ModalHeader>
                <ModalBody>
                  <Row>
                    <Col className="fileContainer">
                      <Button className="btn">
                        {" "}
                        <i className="fa fa-search" /> {multiLanguage.search}{" "}
                        File
                      </Button>
                      <FileBase64
                        multiple={false}
                        onDone={this.getFiles.bind(this)}
                      />{" "}
                      {this.state.files.name}
                      <p />
                      <span className="required">*</span>
                      <span className="label-sizefile">max File 6 MB</span>
                    </Col>
                  </Row>
                </ModalBody>
                <ModalFooter>
                  <Button
                    type="primary"
                    icon="cloud"
                    style={{ width: "76px" }}
                    onClick={this.uploadFile}
                  >
                    {multiLanguage.upload}
                  </Button>
                </ModalFooter>
              </Modal>
              <Button
                outline
                color="success"
                onClick={this.toggle.bind(this, row)}
              >
                {multiLanguage.upload}
              </Button>{" "}
              <Button
                outline
                color="primary"
                onClick={this.download.bind(this, row)}
              >
                {multiLanguage.DownloadLink}
              </Button>
            </div>
          );
        }
      }
    ];
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading...">
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
                    {multiLanguage.managementLetters}
                  </h5>
                </Col>
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
                rowKey={record => record.letter_id}
                columns={columns}
                dataSource={this.state.data}
                pagination={this.state.pagination}
                loading={this.state.loading}
                onChange={this.handleTableChange}
                stripe
              />
            </CardBody>
          </Card>
        </div>
        <NotificationContainer />
      </LoadingOverlay>
    );
  }
}

export default ManageSurat;
