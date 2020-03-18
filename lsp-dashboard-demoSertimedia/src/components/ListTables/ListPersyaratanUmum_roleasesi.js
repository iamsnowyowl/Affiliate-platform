import React, { Component } from "react";
import { Col, Row, Modal, ModalHeader, ModalBody, Label } from "reactstrap";
import { AvForm, AvGroup, AvInput } from "availity-reactstrap-validation";
import { Input, Icon, Table, Button, Popconfirm } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import FileBase64 from "react-file-base64";
import LoadingOverlay from "react-loading-overlay";
import ImageZoom from "react-medium-image-zoom";
import { multiLanguage } from "../Language/getBahasa";
import { Digest } from "../../containers/Helpers/digest";
import {
  path_persyaratanUmum,
  baseUrl,
  getRole,
  getData,
  path_assessments
} from "../config/config";

import iconcontract from "../../assets/img/portofolio/drawable-xxxhdpi/portofolio.png";
import iconxls from "../../assets/img/icon/xlsIcon.png";
import iconrar from "../../assets/img/icon/rar.png";
import iconzip from "../../assets/img/icon/zip.png";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import PDFViewer from "../PDFViewer/PDFViewer";
import PDFJs from "../../backends/pdfjs";

const Search = Input.Search;

type Props = {
  applicant_id: any,
  assessment_id: any
};

class ListPersyaratanUmum_roleasesi extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      offset: 0,
      filteredInfo: null,
      searchText: "",
      files: "",
      filename: "",
      file_name: "",
      loading: false,
      textPortfolio: "",
      form_falue: "",
      disable: false,
      role: false,
      modal: false,
      modalDocumentView: false,
      persyaratan_umum_id: "",
      masterID: "",
      messageSize: "Size Tidak Boleh lebih dari 6 MB",
      sizeOverFize: true
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
    const auth = Digest("/me" + path_persyaratanUmum, "GET");
    reqwest({
      url: baseUrl + "/me" + path_persyaratanUmum + "/?search=" + searchText,
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

  handleChange = event => {
    if (event.target.value === "") {
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = { applicant_id: this.props.applicant_id }) => {
    this.setState({ loading: true });
    const auth = Digest("/me" + path_persyaratanUmum, "GET");
    reqwest({
      url: baseUrl + "/me" + path_persyaratanUmum,
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

  componentDidMount() {
    this.get();
    if (getRole() === "dev") {
      this.setState({
        role: !this.state.role
      });
    }
  }

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    const { applicant_id } = this.props;
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    let type = "UMUM,DASAR";
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
    if (filters.type !== undefined) {
      switch (filters.type[0]) {
        case "UMUM":
          type = "UMUM";
          break;

        case "DASAR":
          type = "DASAR";
          break;

        default:
          break;
      }
    }

    this.get({
      limit: pagination.pageSize,
      applicant_id: applicant_id,
      offset: offset,
      type: type,
      sort: sorting
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleDelete = items => {
    this.setState({
      loading: true
    });
    const persyaratan_umum_id = items.persyaratan_umum_id;
    Axios(
      getData(
        "/me" + path_persyaratanUmum + "/" + persyaratan_umum_id,
        "DELETE",
        null
      )
    ).then(() => {
      this.setState({
        loading: false
      });
      this.get();
    });
  };

  getFiles(row, files) {
    if (files.file.size >= 6008874) {
      window.alert(this.state.messageSize);
    } else {
      this.setState({
        loading: true
      });
      console.log(row);
      const { applicant_id } = this.props;
      const path = "/me" + path_persyaratanUmum;
      var data = {};

      data["master_portfolio_id"] = row.master_portfolio_id;
      data["filename"] = files.name;
      data["applicant_id"] = applicant_id;
      data["form_value"] = files.base64;
      Axios(getData(path, "POST", data)).then(response => {
        console.log(response);
        if (response.status === 201) {
          this.setState({
            loading: false
          });
          this.get();
        }
      });
    }
  }

  handleChangeText = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSubmitText = (event, errors, values) => {
    this.setState({ errors, values });
    const master_portfolio_id = event.master_portfolio_id;
    const { assessment_id } = this.props;
    const path = path_assessments + "/" + assessment_id + "/portfolios";
    var data = {};

    data["master_portfolio_id"] = master_portfolio_id;
    data["form_value"] = this.state.textPortfolio;
    Axios(getData(path, "PUT", data)).then(response => {
      if (response.status === 201) {
        this.setState({
          loading: false
        });
        window.location.reload();
      }
    });
  };

  // toggleChecked = event => {
  //   const master_portfolio_id = event.master_portfolio_id;
  //   const assessment_id = this.props.match.params.assessment_id;
  //   const assessment_applicant_id = this.props.match.params
  //     .assessment_applicant_id;
  //   const persyaratan_umum_id =
  //     event.persyaratan[0].persyaratan_umum_id;
  //   var data = {};
  //   const path =
  //     path_assessments +
  //     '/' +
  //     assessment_id +
  //     path_applicant +
  //     '/' +
  //     assessment_applicant_id +
  //     '/portfolios/' +
  //     persyaratan_umum_id;
  //   data['master_portfolio_id'] = master_portfolio_id;
  //   data['form_value'] = '1';
  //   Axios(getData(path, 'PUT', data)).then(response => {
  //     if (response.status === 200) {
  //       this.setState({
  //         loading: false
  //       });
  //       window.location.reload();
  //     }
  //   });
  // };

  // toggleCheckedFalse = event => {
  //   const master_portfolio_id = event.master_portfolio_id;
  //   const assessment_id = this.props.match.params.assessment_id;
  //   const assessment_applicant_id = this.props.match.params
  //     .assessment_applicant_id;
  //   const persyaratan_umum_id =
  //     event.persyaratan[0].persyaratan_umum_id;
  //   var data = {};
  //   const path =
  //     path_assessments +
  //     '/' +
  //     assessment_id +
  //     path_applicant +
  //     '/' +
  //     assessment_applicant_id +
  //     '/portfolios/' +
  //     persyaratan_umum_id;
  //   data['master_portfolio_id'] = master_portfolio_id;
  //   data['form_value'] = '0';
  //   Axios(getData(path, 'PUT', data)).then(response => {
  //     if (response.status === 200) {
  //       this.setState({
  //         loading: false
  //       });
  //       window.location.reload();
  //     }
  //   });
  // };

  handleEditSubmit = () => {
    const master_portfolio_id = this.state.masterID;
    const { assessment_id } = this.props;
    const persyaratan_umum_id = this.state.persyaratan_umum_id;
    var data = {};
    const path =
      path_assessments +
      "/" +
      assessment_id +
      "/portfolios/" +
      persyaratan_umum_id;
    data["master_portfolio_id"] = master_portfolio_id;
    data["form_value"] = this.state.url;

    Axios(getData(path, "PUT", data)).then(res => {
      if (res.status === 200) {
        this.setState({
          loading: false
        });
        window.location.reload();
      }
    });
  };

  handleEditLink = () => {
    const master_portfolio_id = this.state.masterID;
    // const { applicant_id } = this.props;
    var data = {};
    const path = path_persyaratanUmum;
    data["master_portfolio_id"] = master_portfolio_id;
    data["form_value"] = this.state.url;

    Axios(getData(path, "POST", data)).then(res => {
      this.setState({
        loading: false
      });
      window.location.reload();
    });
  };

  toggle = row => {
    this.setState({
      modal: !this.state.modal,
      persyaratan_umum_id: row.persyaratan[0].persyaratan_umum_id,
      masterID: row.master_portfolio_id
    });
  };

  toggleDocummentView = (value, form_desc) => {
    var DocName = form_desc;
    var url = baseUrl + value.form_value;
    this.setState({
      modalDocumentView: !this.state.modalDocumentView,
      urlDocument: url,
      file_name: DocName
    });
  };

  toggleClose = () => {
    this.setState({
      modal: false
    });
  };

  Link = value => {
    window.open("http://" + value);
  };

  handleEditChange = event => {
    this.setState({
      [event.target.name]: event.target.value
    });
  };

  cancel = () => {
    console.log("cancel");
  };

  render() {
    const { sizeOverFize } = this.state;
    console.log(sizeOverFize);
    const columns = [
      {
        key: "form_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.PortfolioName}
          </h5>
        ),
        dataIndex: "form_name",
        sorter: true,
        width: "40%",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "type",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.PortfolioType}
          </h5>
        ),
        dataIndex: "type",
        // filters: [
        //   { text: 'Umum', value: 'UMUM' },
        //   { text: 'Dasar', value: 'DASAR' }
        // ],
        render: value => {
          if (value === "UMUM") {
            return <div style={{ textAlign: "center" }}>Umum</div>;
          } else {
            return <div style={{ textAlign: "center" }}>Dasar</div>;
          }
        }
      },
      {
        key: "persyaratan",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.file}
          </h5>
        ),
        dataIndex: "persyaratan",
        width: "25%",
        render: (value, row) => {
          if (row.form_type === "file") {
            return (
              <div>
                {value.map((items, key) => (
                  <ul key={key}>
                    <li key={items}>
                      {items.ext === "file" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              onClick={() =>
                                this.toggleDocummentView(
                                  baseUrl + items.form_value,
                                  row.form_name
                                )
                              }
                            >
                              <img
                                style={{ width: "25px", height: "25px" }}
                                alt=""
                                src={iconcontract}
                              />
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "doc" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <a
                                href={baseUrl + items.form_value}
                                target="_blank"
                              >
                                <img
                                  style={{ width: "25px", height: "25px" }}
                                  alt=""
                                  src={iconcontract}
                                />
                              </a>
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "docx" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <a
                                href={baseUrl + items.form_value}
                                target="_blank"
                              >
                                <img
                                  style={{ width: "25px", height: "25px" }}
                                  alt=""
                                  src={iconcontract}
                                />
                              </a>
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "pdf" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              onClick={() =>
                                this.toggleDocummentView(items, row.form_name)
                              }
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <img
                                style={{ width: "25px", height: "25px" }}
                                alt=""
                                src={iconcontract}
                              />
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "xls" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <a
                                href={baseUrl + items.form_value}
                                target="_blank"
                              >
                                <img
                                  style={{ width: "30px", height: "30px" }}
                                  alt=""
                                  src={iconxls}
                                />
                              </a>
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              onClick={this.handleDelete.bind(this, items)}
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "xlsx" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <a
                                href={baseUrl + items.form_value}
                                target="_blank"
                              >
                                <img
                                  style={{ width: "30px", height: "30px" }}
                                  alt=""
                                  src={iconxls}
                                />
                              </a>
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "rar" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <a
                                href={baseUrl + items.form_value}
                                target="_blank"
                              >
                                <img
                                  style={{ width: "30px", height: "30px" }}
                                  alt=""
                                  src={iconrar}
                                />
                              </a>
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "zip" ? (
                        <Row>
                          <Col md="3">
                            <Button
                              style={{
                                border: "none",
                                backgroundColor: "transparent"
                              }}
                            >
                              <a
                                href={baseUrl + items.form_value}
                                target="_blank"
                              >
                                <img
                                  style={{ width: "30px", height: "30px" }}
                                  alt=""
                                  src={iconzip}
                                />
                              </a>
                            </Button>
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>
                        </Row>
                      ) : items.ext === "" ? (
                        <div>{multiLanguage.noFile}</div>
                      ) : (
                        <Row>
                          <Col md="3" style={{ textAlign: "center" }}>
                            <ImageZoom
                              image={{
                                src: baseUrl + items.form_value,
                                style: { width: "2.5em" }
                              }}
                              zoomImage={{
                                src: baseUrl + items.form_value,
                                alt: ""
                              }}
                            />
                          </Col>
                          <Col md="5">{items.filename}</Col>
                          <Col>
                            <button
                              className="btn btn-danger btn-sm"
                              title={multiLanguage.delete}
                            >
                              <Popconfirm
                                title="Anda yakin menghapus data ini?"
                                onConfirm={this.handleDelete.bind(this, items)}
                                onCancel={this.cancel}
                                okText="YA"
                                cancelText="Tidak"
                              >
                                {multiLanguage.delete}
                              </Popconfirm>
                            </button>
                          </Col>{" "}
                        </Row>
                      )}
                    </li>
                  </ul>
                ))}
              </div>
            );
          } else if (row.form_type === "text") {
            return (
              <div>
                {value.map((items, key) => (
                  <ul key={key}>
                    <li key={items}>
                      <Row>
                        <Col>{items.form_value}</Col>
                      </Row>
                    </li>
                  </ul>
                ))}
              </div>
            );
          } else if (row.form_type === "checkbox") {
            return (
              <div>
                {value.map((items, key) => (
                  <ul key={key}>
                    <li key={items}>
                      <Row>
                        <Col>
                          {items.form_value === "1"
                            ? multiLanguage.yes
                            : items.form_value === "0"
                            ? multiLanguage.no
                            : "-"}
                        </Col>
                        <Col>
                          <button
                            className="btn btn-danger btn-sm"
                            onClick={this.handleDelete.bind(this, items)}
                            title={multiLanguage.delete}
                          >
                            {multiLanguage.delete}
                          </button>
                        </Col>
                      </Row>
                    </li>
                  </ul>
                ))}
              </div>
            );
          } else if (row.form_type === "file_online") {
            return (
              <div className="online" style={{ textAlign: "center" }}>
                Online Form
              </div>
            );
          } else {
            return "gada";
          }
        }
      },
      {
        key: "master_portfolio_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "master_portfolio_id",
        render: (value, row) => {
          if (row.form_type === "file_online") {
            return (
              <Row>
                <Col>
                  <div>
                    <Button
                      type="submit"
                      color="success"
                      onClick={this.toggle.bind(this, row)}
                    >
                      <i className="fa fa-globe" /> Edit Link Form
                    </Button>{" "}
                    {row.persyaratan.length === 0 ? (
                      "Belum Ada Link"
                    ) : (
                      <a
                        href={`${row.persyaratan[0].form_value}`}
                        className="btn btn-formOnline"
                        role="button"
                        title="Form Online"
                        target="_blank"
                      >
                        <i className="fa fa-globe" /> Form Online
                      </a>
                    )}
                  </div>
                </Col>
              </Row>
            );
          } else {
            return (
              <div>
                <Row>
                  <Col className="fileContainer">
                    <Button className="btn">
                      {" "}
                      <i className="fa fa-search" /> {multiLanguage.search} File
                    </Button>
                    <FileBase64
                      multiple={false}
                      onDone={this.getFiles.bind(this, row)}
                    />{" "}
                    <span className="required">*</span>
                    <span className="label-sizefile">max File 6 MB</span>
                  </Col>
                </Row>
              </div>
            );
          }
        }
      }
    ];

    // const { assessment_id } = this.props;
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading..">
        <div className="animated fadeIn">
          <Modal
            isOpen={this.state.modalDocumentView}
            toggle={this.toggleDocummentView}
            size="lg"
            style={{ width: "635px" }}
          >
            <ModalHeader toggle={this.toggleDocummentView}>
              {this.state.file_name}
            </ModalHeader>
            <ModalBody>
              <PDFViewer backend={PDFJs} src={this.state.urlDocument} />
            </ModalBody>
          </Modal>
          <Modal isOpen={this.state.modal} toggle={this.toggleClose}>
            <ModalHeader>Edit Link Form Online</ModalHeader>
            <ModalBody>
              <AvForm
                action=""
                encType="multipart/form-data"
                className="form-horizontal"
              >
                <AvGroup row>
                  <Col md="4">
                    <Label htmlFor="type">Link URL</Label>
                  </Col>
                  <Col xs="5" md="4">
                    <AvInput
                      type="url"
                      id="url"
                      name="url"
                      onChange={this.handleEditChange}
                    />
                  </Col>
                </AvGroup>
                <Button color="success" onClick={this.handleEditLink}>
                  <i className="fa fa-check" /> Ok
                </Button>{" "}
                <Button color="danger" onClick={this.toggleClose}>
                  {multiLanguage.cancel}
                </Button>
                <p />
              </AvForm>
              <p />
            </ModalBody>
          </Modal>
          {`${multiLanguage.searching} `}
          <Search
            placeholder={multiLanguage.search}
            onSearch={this.handleSearch}
            onChange={this.handleChange}
            style={{ width: 310 }}
          />{" "}
          <p />
          <Table
            rowKey={record => record.master_portfolio_id}
            columns={columns}
            dataSource={this.state.data}
            pagination={this.state.pagination}
            loading={this.state.loading}
            onChange={this.handleTableChange}
            stripe
          />
        </div>
      </LoadingOverlay>
    );
  }
}

export default ListPersyaratanUmum_roleasesi;
