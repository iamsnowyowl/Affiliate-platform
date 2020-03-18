import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody } from "reactstrap";
import { Input, Button, Icon, Table, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";

import {
  path_assessments,
  baseUrl,
  formatDate
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import ButtonDelete from "../../components/Button/ButtonDelete";
// import style from '../../css/style.css';

const Search = Input.Search;

class Rejected extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: ""
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

  get = (params = { last_activity_state: "ASSESSMENT_REJECTED" }) => {
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
      last_activity_state: "ASSESSMENT_REJECTED"
    });
  };

  render() {
    const columns = [
      {
        key: "title",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentName}
          </h5>
        ),
        dataIndex: "title"
      },
      {
        key: "address",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.address}
          </h5>
        ),
        dataIndex: "address",
        sorter: true
      },
      {
        key: "start_date",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentDate}
          </h5>
        ),
        dataIndex: "start_date",
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "tuk_name",
        title: <h5 style={{ fontWeight: "bold", textAlign: "center" }}>TUK</h5>,
        dataIndex: "tuk_name",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "created_date",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.tglSubmit}
          </h5>
        ),
        dataIndex: "created_date",
        sorter: true,
        render: value => {
          const dateFormat = formatDate(value);
          return <div style={{ textAlign: "center" }}>{dateFormat}</div>;
        }
      },
      {
        key: "last_activity_state",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>Status</h5>
        ),
        dataIndex: "last_activity_state",
        sorter: true,
        render: value => {
          if (value === "ASSESSMENT_REJECTED") {
            return (
              <div style={{ textAlign: "center", color: "red" }}>
                {multiLanguage.reject}
              </div>
            );
          } else {
            return value;
          }
        }
      },
      {
        key: "last_activity_description",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.note}
          </h5>
        ),
        dataIndex: "last_activity_description",
        sorter: true,
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "assessment_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_id",
        sorter: true,
        render: value => {
          return (
            <div>
              <ButtonDelete id_delete={value} path={path_assessments} />
            </div>
          );
        }
      }
    ];
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
                  {multiLanguage.listRejected}
                </h5>
              </Col>
              <Col md="6" className="mb-3 mb-xl-0" />
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

export default Rejected;
