import axios from 'axios'

const url = getUrl()

const api = async (config) => {
  try {
    const { data } = await axios.create({ baseURL: url })(config)
    return data
  } catch (error) {
    if (error.response) {
      const response = error.response
      if (response.status === 422) {
        const data = response.data.data
        const property = Object.keys(data)[0]
        throw data[property][0]
      }
      throw response.data.message
    }
    throw error.message
  }
}

export default api