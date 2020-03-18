import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Button,
  Card,
  CardHeader,
  CardBody,
  Row,
  Col,
  Label
} from "reactstrap";
import { Input, Icon, Table, Popconfirm } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import moment from "moment";

import {
  path_assessments,
  baseUrl,
  path_pleno,
  getData
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import Axios from "axios";

const Search = Input.Search;

type Props = {
  payloadDetail: any
};
export default class DetailPleno extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      runs: ""
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
    const { assessment_id } = this.props.params;
    var url =
      baseUrl +
      path_assessments +
      "/" +
      assessment_id +
      path_pleno +
      "?search=" +
      searchText;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + path_pleno,
      "GET"
    );
    reqwest({
      url: url,
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
    const { assessment_id } = this.props.params;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + path_pleno,
      "GET"
    );
    reqwest({
      url: baseUrl + path_assessments + "/" + assessment_id + path_pleno,
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
        console.log("response", response);
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

  componentDidMount = () => {
    const runs = this.props.run;
    this.get();
    this.setState({
      runs: runs
    });
  };

  handleTableChange = (pagination, _filters, sorter) => {
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
    });
  };

  handleChangeDate = event => {
    this.setState({
      [event.target.name]: event.target.value,
      detail: false,
      user_id: event.target.value
    });
  };

  handleSubmit = event => {
    event.preventDefault();
    const { assessment_id } = this.props.params;
    var pleno_date = moment(this.state.pleno_date).format();
    var data = {};
    data["pleno_date"] = pleno_date;
    const path = path_assessments + "/" + assessment_id;
    const method = "PUT";
    Axios(getData(path, method, data)).then(response => {
      if ((response.status = 200)) {
        window.location.reload();
      }
    });
  };

  handleDelete = value => {
    const { assessment_id } = this.props.params;
    this.setState({
      loading: true
    });

    Axios(
      getData(
        path_assessments + "/" + assessment_id + path_pleno + "/" + value,
        "DELETE"
      )
    ).then(() => {
      this.setState({
        loading: false
      });
      this.get();
    });
  };

  render() {
    const {
      last_activity_state,
      assessment_id,
      pleno_date
    } = this.props.payloadDetail;
    const columns = [
      {
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        render: (value, row) => {
          const full = value + " " + row.last_name;
          return <div>{full}</div>;
        }
      },
      {
        key: "contact",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.contact}
          </h5>
        ),
        dataIndex: "contact",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "position",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.position}
          </h5>
        ),
        dataIndex: "position",
        render: text => {
          return text;
          // if (text === "ketua") {
          //   return "Ketua";
          // } else if (text === "ANGGOTA_1") {
          //   return "Anggota 1";
          // } else if (text === "ANGGOTA_2") {
          //   return "Anggota 2";
          // }
        }
      },
      {
        key: "assessment_pleno_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_pleno_id",
        render: value => {
          return (
            <div>
              <Popconfirm
                title={multiLanguage.confirmDelete}
                onConfirm={this.handleDelete.bind(this, value)}
                onCancel={this.cancel}
                okText={multiLanguage.yes}
                cancelText={multiLanguage.no}
              >
                <button
                  className="btn btn-danger delete-button col-md-auto"
                  title={multiLanguage.Delete}
                >
                  <i className="fa fa-trash"></i>
                </button>
              </Popconfirm>
            </div>
          );
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            <Row>
              <Col md="1.5" style={{ textAlign: "center" }}>
                <Label htmlFor="pleno_date">{multiLanguage.plenoDate}</Label>
              </Col>
              <Col xs="2" md="1.5">
                <Input
                  type="date"
                  name="pleno_date"
                  defaultValue={moment(pleno_date).format("YYYY-MM-DD")}
                  onChange={this.handleChangeDate}
                />
              </Col>
              <Col xs="2" md="1.5">
                <Button
                  className="btn-success"
                  onClick={this.handleSubmit}
                  style={{ fontSize: "13px" }}
                >
                  {" "}
                  {multiLanguage.ChangePlenoDate}
                </Button>
              </Col>
              <Col>
                {last_activity_state === "REAL_ASSESSMENT" ? (
                  <Link
                    to={
                      path_assessments +
                      "/" +
                      assessment_id +
                      path_pleno +
                      "/" +
                      this.state.runs
                    }
                  >
                    <Button className="float-md-right" size="md">
                      <i className="fa fa-plus" /> {multiLanguage.plenoMember}
                    </Button>
                  </Link>
                ) : (
                  ""
                )}
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
              rowKey={record => record.pleno_id}
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
