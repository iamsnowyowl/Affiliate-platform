import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Input, Icon, Table, Popconfirm, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import { AvForm, AvField, AvGroup } from "availity-reactstrap-validation";

import {
  path_assessments,
  baseUrl,
  getData,
  formatDate
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
// import style from '../../css/style.css';

const Search = Input.Search;
class Submissions extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      assessment_id: "",
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      modal: false,
      payload: [],
      payloadUserData: [],
      visible: false
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
    const auth = Digest(path_assessments, "GET");
    reqwest({
      url: baseUrl + path_assessments + "?search=" + searchText,
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

  get = (
    params = {
      last_activity_state: "TUK_SEND_REQUEST_ASSESSMENT"
    }
  ) => {
    this.setState({ loading: true });
    const auth = Digest(path_assessments, "GET");
    reqwest({
      url: baseUrl + path_assessments,
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
    var json = JSON.parse(localStorage.getItem("userdata"));
    this.setState({
      payloadUserData: json
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
      sort: sorting,
      last_activity_state: "TUK_SEND_REQUEST_ASSESSMENT"
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleChangeModal = event => {
    this.setState({
      [event.target.name]: event.target.value
    });
  };

  handleReject = value => {
    this.setState({
      visible: true,
      assessment_id: value
    });
  };

  submitReject = () => {
    const path =
      path_assessments +
      "/" +
      this.state.assessment_id +
      "/change_state/ASSESSMENT_REJECTED";
    const data = {};
    data["last_activity_description"] = this.state.note;
    Axios(getData(path, "PUT", data)).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({ loading: true });
        setTimeout(() => {
          this.setState({
            loading: false,
            visible: false
          });
        }, 1000);
        this.get();
      } else {
        alert("Cannot confirm");
      }
    });
  };

  cancel = () => {
    this.setState({
      visible: false
    });
  };

  confirmTUK = assessment_id => {
    this.setState({
      loading: true
    });
    Axios(
      getData(
        path_assessments +
          "/" +
          assessment_id +
          "/change_state/ADMIN_CONFIRM_FORM",
        "PUT"
      )
    ).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({ loading: true });
        setTimeout(() => {
          this.setState({
            loading: false
          });
        }, 1000);
        this.get();
      } else {
        alert("Cannot confirm");
      }
    });
  };

  render() {
    const columns = [
      {
        key: "title",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.assessmentName}</h5>
        ),
        width: "20%",
        align: "center",
        dataIndex: "title",
        render: (value, row) => {
          console.log(row);
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "schema_label",
        title: <h5 style={{ fontWeight: "bold" }}>Nama Skema</h5>,
        dataIndex: "schema_label",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "address",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.address}</h5>,
        dataIndex: "address",
        sorter: true,
        render: value => {
          return value;
        }
      },
      {
        key: "start_date",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentDate}
          </h5>
        ),
        width: "10%",
        dataIndex: "start_date",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "tuk_name",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.tukName}</h5>,
        width: "10%",
        dataIndex: "tuk_name",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "request_date",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.requestDate}
          </h5>
        ),
        dataIndex: "request_date",
        render: value => {
          return <div>{formatDate(value)}</div>;
        }
      },
      {
        key: "assessment_id",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        dataIndex: "assessment_id",
        width: "20%",
        render: (value, row) => {
          if (row.last_activity_state === "TUK_SEND_REQUEST_ASSESSMENT") {
            return (
              <div>
                <Button
                  className="btn btn-success "
                  onClick={() => this.confirmTUK(row.assessment_id)}
                >
                  {multiLanguage.approve}
                </Button>{" "}
                <a
                  href={row.request_letter_url}
                  className="btn btn-warning col-md-auto confrim"
                  title={multiLanguage.viewDocument}
                  target="_blank"
                >
                  <i className="fa fa-file-pdf-o"></i>
                </a>{" "}
                <Popconfirm
                  title={multiLanguage.confirmReject}
                  onConfirm={() => this.handleReject(row.assessment_id)}
                  onCancel={this.cancel}
                  okText={multiLanguage.yes}
                  cancelText={multiLanguage.no}
                >
                  <Button
                    className="btn btn-danger col-md-auto reject"
                    // onClick={this.handleReject.bind(this, row.assessment_id)}
                  >
                    {multiLanguage.reject}
                  </Button>
                </Popconfirm>
              </div>
            );
          }
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <Modal
          title="Note"
          visible={this.state.visible}
          onOk={this.submitReject}
          onCancel={this.cancel}
        >
          <AvForm>
            <AvGroup row>
              <Col>
                <AvField
                  type="text"
                  className="borderInput"
                  name="note"
                  label={multiLanguage.labelModalReject}
                  onChange={this.handleChangeModal}
                  required
                />
              </Col>
            </AvGroup>
          </AvForm>
        </Modal>
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
                  Data {multiLanguage.Assessment}
                </h5>
              </Col>
              {/* {getRole() === 'ACS' || getRole() === 'APL' ? (
                ''
              ) : (
                <Col md="6" className="mb-3 mb-xl-0">
                  <Link to={path_assessments + '/input-data'}>
                    <Button className="float-md-right" size="default" color="primary">
                      <i className="fa fa-plus" />{' '}
                      {multiLanguage.add + ' ' + multiLanguage.Assessment}
                    </Button>
                  </Link>
                </Col>
              )} */}
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
              rowKey={record => record.assessment_id}
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
    );
  }
}

export default Submissions;
